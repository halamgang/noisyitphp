<?php
session_start();
$remainingAttempts = 0; // or any other default value
$string = file_get_contents("/login/users.json");

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

$string = file_get_contents("/login/users.json");
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

file_put_contents("/login/users.json", json_encode($json_a, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>

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
echo "<p class='game-title'>반응속도 테스트</p>";
echo "<p class='small-text'>";
echo "<p style='color:#1ECD97;'>오류시 <a href='' onclick='window.location.reload();' style='color:#1ECD97;'>새로고침(클릭)</a> 해주세요!</p>";
echo "<span style='color:#1ECD97;'>$username 님은 현재  ".$point." P 보유중</span>";
echo "</p>";
?>
</div>

<div class="line"></div>

<!-- 변경된 부분: 시작하기 버튼 추가 -->
<button type="button" id="start-button">시작하기</button>

<!-- 변경된 부분: 반응속도 결과를 표시하는 컨테이너 추가 -->
<div id="click-count-container">
    <p>반응속도: <span id="reaction-time"></span>ms</p>
    <p>평균반응속도: 200ms</p>
    <p>평균반응속도보다 빠르다면! 포인트가 지급됩니다! 단! 느리면 차감됩니다!</p>
</div>

<script>
var isGameStarted = false; // 게임 시작 여부를 나타내는 변수
var startTime; // 게임 시작 시간을 저장하는 변수

// 변경된 부분: 시작하기 버튼 클릭 이벤트 처리
document.getElementById('start-button').addEventListener('click', function() {
    if (!isGameStarted) { // 게임이 시작되지 않은 경우에만 처리
        isGameStarted = true;
        this.disabled = true;
        
        setTimeout(function() {
            document.body.style.backgroundColor = 'red'; // 화면을 빨강으로 변경
            
            startTime = new Date().getTime(); // 게임 시작 시간 기록
            
            document.addEventListener('click', handleClick); // 클릭 이벤트 리스너 등록
        }, getRandomTime()); // 랜덤한 시간 후에 화면 색상 변경
        
    }
});

function handleClick() {
    if (isGameStarted) { // 게임이 진행 중인 경우에만 처리
        var endTime = new Date().getTime(); // 클릭 시간 기록
        var reactionTime = endTime - startTime; // 반응 속도 계산 (ms)
        
        if (document.body.style.backgroundColor !== 'red') {
            alert('화면이 아직 빨갛게 변하지 않았습니다. 조금만 기다려주세요!');
            return;
        }
        
        document.body.style.backgroundColor = 'white'; // 화면을 원래대로 변경
        
        document.getElementById('reaction-time').textContent = reactionTime; // 반응속도 표시
        
        if (reactionTime < 200) {
            updatePoints(50); // 평균반응속도보다 빠를 경우 포인트 추가 (예시로 50 포인트 추가)
        } else {
            updatePoints(-30); // 평균반응속도보다 느릴 경우 포인트 감소 (예시로 30 포인트 감소)
        }
        
        isGameStarted = false;
        
      setTimeout(function() {
          document.getElementById('start-button').disabled=false;
          document.getElementById('reaction-time').textContent='';
      }, 1000);
      
      document.removeEventListener('click', handleClick); 
    }
}

function getRandomTime() {
    return Math.floor(Math.random() * 5000) + 1000; 
}

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
  