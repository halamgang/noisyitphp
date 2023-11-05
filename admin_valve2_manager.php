<?php
session_start();

if(!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: /system/admin/admin_site.php');
    exit;
}
?>
<?php
session_start();

$alertMessage = '';

if (isset($_POST['load'])) {
    $jsonData = file_get_contents('login/users.json');
    $jsonData = json_encode(json_decode($jsonData, true), JSON_PRETTY_PRINT);
}


if (isset($_POST['save'])) {
    $selectedField = 'point';
    $valueToAdd = (float)$_POST['plus'];

    // JSON 데이터 불러오기
    $jsonArray = json_decode(file_get_contents('login/users.json'), true);

    $newJsonArray = array();

    foreach ($jsonArray as $user) {
        if (isset($user['username']) && isset($user[$selectedField])) {
            // 보상 포인트 계산 및 추가
            $username = $user['username'];
            $user[$selectedField] = $user[$selectedField] + ($user['betAmount'] * $valueToAdd);
            $newJsonArray[] = $user;
        } else {
            $newJsonArray[] = $user;
        }
    }

    $jsonData = json_encode($newJsonArray, JSON_PRETTY_PRINT);
    file_put_contents('login/users.json', $jsonData);
    $alertMessage = '저장완료';
}
?>
<!DOCTYPE html>
<html>
<head>
  
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .all_class {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    textarea, input[type="password"], select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
    }
    button {
      padding: 15px 30px;
      background-color: #0074d9;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 18px;
    }
    textarea, input[type="number"], select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
    }
    number {
      padding: 15px 30px;
      background-color: #0074d9;
      color: #fff;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 18px;
    }
  </style>
  <script>
    function showAlert(message) {
        alert(message);
    }
  </script>
</head>
<body>
  <div class="all_class">
    <h2>전체유저 관리 | 관리자용</h2>
    <form method="post" action="">
      <textarea cols="100" rows="30" name="json_data" placeholder="json 수정 함부로 금지"><?= $jsonData ?></textarea>
      <br>
      <select name="json_file">
        <option value="point">point</option>
        <option value="value2">value2</option>
      </select>
      <br>
      <input type="plus" name="plus" placeholder="추가값">
      <br>
      <button type="submit" name="load">불러오기</button>
      <button type="submit" name="save" onclick="showAlert('<?= $alertMessage ?>')">저장</button>
    </form>
  </div>
</body>
</html>
