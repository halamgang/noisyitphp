<?php
session_start();
$remainingAttempts = 0;
$clickCount = 0;
$string = file_get_contents("../login/users.json");

if ($string === false) {
    die('웹서버를 불러오는 도중 실패했습니다. 문의하세요.');
}

$json_a = json_decode($string, true);

if ($json_a === null) {
    die('웹서버를 불러오는 도중 실패했습니다. 문의하세요');
}

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $username = "미가입자";
}

$string = file_get_contents("../login/users.json");
$json_a = json_decode($string, true);

$point = "0"; 

foreach ($json_a as &$user) { 
    if ($user['username'] == $username) { 
        $point = $user['point']; 
        if (isset($user['value2'])) {
            $remainingAttempts = intval($user['value2']); 
            if ($remainingAttempts > 0) {
                $remainingAttempts--; 
                $user['value2'] = strval($remainingAttempts); 
            }
        }

        // 해당 부분 추가: 사용자 포인트 업데이트
        $user['point'] = strval($point);

        break;
    }
}

if ($remainingAttempts <= 0) {
    echo "<script>document.getElementById('start-button').disabled=true;</script>";
}
file_put_contents("../login/users.json", json_encode($json_a, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clickCount = $_POST['clickCount'];

    if ($clickCount >= 15) {
        $expiry = time() + (24 * 60 * 60);
        setcookie('ban_game', '1', $expiry, '/');
        echo 'ban';
        exit();
    }
}
?>
<?php
// 세션에 저장된 username 값 확인
if(isset($_SESSION['username']) && $_SESSION['username'] === '11011') {
  // username이 11011인 경우 "/game/ban_users.php"로 이동
  header("Location: /game/ban_users.php");
  exit;
} else {
}
?>
<script type="text/javascript">
    var clickCount = 0;
    var timer;

    document.addEventListener('click', function() {
        clickCount++;
    });

    timer = setTimeout(function() {
        if (clickCount >= 10) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'game3.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = xhr.responseText;
                    if (response === 'ban') {
                        alert('오토마우스 감지');
                        window.location.href = '/index.php';
                    }
                }
            };
            xhr.send('clickCount=' + clickCount);
        }
    }, 1000);
</script>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<link href="game.css" rel="stylesheet" />
<div class="team-box" data-team="">
<div id="result-container">
<?php
echo "<p class='game-title'>클릭게임! 쉬운편</p>";
echo "<p class='small-text'>";
echo "<span style='color:#1ECD97;'>$username 님은 현재  ".$point." P 보유중</span>";
echo "</p>";
?>
</div>

<div class="line"></div>

<!-- 변경된 부분: 클릭량과 남은 시간을 표시하는 텍스트 추가 -->
<div id="click-count-container">
    <p>클릭량: <span id="click-count">0</span></p>
    <p>남은 시간: <span id="remaining-time">10</span>초</p>
    <p>10초동안 100회를 클릭하면 100포인트! 실패시 -50포인트가 차감됩니다!</p>
</div>

<!-- 변경된 부분: 시작하기 버튼 추가 -->
<button type="button" id="start-button">시작하기</button>

<script>
var clickCount = 0; // 클릭량 변수 추가
var remainingTime = 10; // 남은 시간 변수 추가
var timer; // 타이머 변수

// 변경된 부분: 시작하기 버튼 클릭 이벤트 처리
document.getElementById('start-button').addEventListener('click', function() {
    // 시작하기 버튼 비활성화
    this.disabled = true;

    // 변경된 부분: 1초마다 남은 시간 감소 및 업데이트
    timer = setInterval(function() {
        remainingTime--;
        document.getElementById('remaining-time').textContent = remainingTime;

        if (remainingTime <= 0) {
            clearInterval(timer); // 타이머 중지

            if (clickCount >= 100) {
                alert('성공');
                updatePoints(100); // 성공 시 포인트 추가 (예시로 100 포인트 추가)
            } else {
                alert('실패');
                updatePoints(-50); // 실패 시 포인트 감소 (예시로 50 포인트 감소)
            }

            window.location.href = '/index.php'; // index.php로 페이지 이동
        }
    }, 1000);
});

// 변경된 부분: 화면 클릭 이벤트 처리
document.addEventListener('click', function() {
    clickCount++; // 클릭량 증가
    document.getElementById('click-count').textContent = clickCount; // 클릭량 업데이트
});

function updatePoints(delta) { 
     var xhrPointUpdateRequest= new XMLHttpRequest();
     xhrPointUpdateRequest.open("POST", "update_points.php", true);
     xhrPointUpdateRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

     xhrPointUpdateRequest.onreadystatechange = function () {
         if (xhrPointUpdateRequest.readyState === 4 && xhrPointUpdateRequest.status === 200) {
             document.getElementById('user-point').textContent=xhrPointUpdateRequest.responseText + ' P';

             var pointChangeElement=document.getElementById('user-point-change');
             pointChangeElement.textContent=(delta >0 ? '+':'')+delta+' P';

             if(delta>0){
                 pointChangeElement.style.color='blue';
             }else{
                 pointChangeElement.style.color='red';
             }
         }
     };

     var params ="username=" + encodeURIComponent(<?php echo json_encode($username); ?>)+"&delta="+delta;

      xhrPointUpdateRequest.send(params);
}
</script>

</body>
</html>
