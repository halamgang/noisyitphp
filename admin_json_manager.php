<?php
session_start();

if(!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: /system/admin/admin_site.php');
    exit;
}
?>
<?php
$jsonFilePaths = [
    "system/1nd2/a_data.json",
    "system/1nd2/b_data.json",
    "system/1nd2/a_2_data.json",
    "system/1nd2/b_2_data.json",
    "system/1nd2/time.json"
];

// 비밀번호
$password = "nt2023!!";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["save"]) && $_POST["password"] == $password) {
        $jsonData = $_POST["json_data"];
        $selectedFile = $_POST["json_file"];
        $index = intval($selectedFile) - 1;

        if (array_key_exists($index, $jsonFilePaths)) {
            $jsonFilePath = $jsonFilePaths[$index];
            file_put_contents($jsonFilePath, $jsonData);
            $message = "JSON 파일이 성공적으로 저장되었습니다.";
        }
    } elseif (isset($_POST["load"])) {
        $selectedFile = $_POST["json_file"];
        $index = intval($selectedFile) - 1;

        if (array_key_exists($index, $jsonFilePaths)) {
            $jsonFilePath = $jsonFilePaths[$index];
            $jsonData = file_get_contents($jsonFilePath);
        }
    }
} else {
    $jsonData = "";
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
  </style>
</head>
<body>
  <div class="all_class">
    <h2>JSON 관리 | 관리자용</h2>
    <form method="post" action="">
      <textarea cols="100" rows="30" name="json_data" placeholder="json 수정 함부로 금지"><?= $jsonData ?></textarea>
      <br>
      <select name="json_file">
        <?php foreach ($jsonFilePaths as $index => $filePath): ?>
          <option value="<?= $index + 1 ?>">파일 <?= $index + 1 ?></option>
        <?php endforeach; ?>
      </select>
      <br>
      <input type="password" name="password" placeholder="비밀번호">
      <br>
      <button type="submit" name="load">불러오기</button>
      <button type="submit" name="save">저장</button>
    </form>
    <p><?= $message ?></p>
  </div>
</body>
</html>
