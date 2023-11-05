<?php
session_start();
$remainingAttempts = 0;
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
echo "<p class='game-title'>랜덤의! 가위바위보 게임! 베팅하자!</p>";
echo "<p class='small-text'>";
echo "<p style='color:#1ECD97;'>오류시 <a href='' onclick='window.location.reload();' style='color:#1ECD97;'>새로고침(클릭)</a> 해주세요!</p>";
echo "<span style='color:#1ECD97;'>$username 님은 현재  ".$point." P 보유중</span>";
echo "</p>";
?>
</div>

<div class="line"></div>

<div class="input-container">
<label for="choice-input">가위</label>
<input type="radio" id="choice-input-scissors" name ="choice" value ="가위">

<label for ="choice-input">바위</label>  
<input type ="radio"id ="choice-input-rock"name ="choice"value ="바위">

<label for ="choice-input">보</label>  
<input type ="radio"id ="choice-input-paper"name ="choice"value ="보">
</div>

<div class="input-container">
<input type="text" id="amount-input" placeholder="금액 입력">

<form method="POST" action="" onsubmit="event.preventDefault(); startGame();">
<button type="submit" id="start-button">시작하기</button>
</form>

</div>

<div id="result-container">
<?php
echo "<p class='small-text'>";
echo "<p style='color:#1ECD97;'>AI_결과: <span id='ai-result'></span></p>";
echo "<p style='color:#1ECD97;'>사용자_결과: <span id='user-result'></span></p>";
echo "<p style='color:#1ECD97;'>사용자_포인트: <span id='user-point'>" . $point . " P</span> (<span id='user-point-change'></span>)</p>";    
echo "<p style='color:#1ECD97;'>남은 횟수: <span id='remaining-attempts'>" . $remainingAttempts . "</span> | <a href='#' onclick=\"chargePoints()\" style='color:#1ECD97;'>충전하기</a></p>";
echo "<p style='color:#1ECD97;'>충전요금 1000포인트 | 하루 2회 무료</p>";
?>
</div>

    </div>   

<script>
function startGame() {
    var choiceScissors = document.getElementById("choice-input-scissors");
    var choiceRock = document.getElementById("choice-input-rock");
    var choicePaper = document.getElementById("choice-input-paper");
    var amountInput = document.getElementById("amount-input");
  var aiResultText = document.getElementById("ai-result");
  var userResultText = document.getElementById("user-result");

  if (choiceScissors.checked || choiceRock.checked || choicePaper.checked) {
    if (amountInput.value !== "") {
      var amount = parseInt(amountInput.value);
      var userPointElement = document.getElementById('user-point');
      var userPoint=parseInt(userPointElement.textContent);

      if(amount > userPoint){
        alert('가지고 있는 포인트보다 많은 금액을 베팅할 수 없습니다.');
        return;
      }

      aiResultText.textContent= ""; 
      userResultText.textContent= ""; 

      var remainingAttemptsElement = document.getElementById('remaining-attempts');
      var remainingAttempts = parseInt(remainingAttemptsElement.textContent);

      if (remainingAttempts <= 0) {
        alert('더 이상 플레이할 수 있는 횟수가 없습니다.');
        return;
      }

      aiResultText.textContent= ""; 
      userResultText.textContent= ""; 

            // AI 가위, 바위, 보 선택
            const choicesArray=['가위','바위','보'];
            const randomIndex=Math.floor(Math.random()*choicesArray.length);
            const aiChoice=choicesArray[randomIndex];

            // 사용자 선택
            let userChoice;
            if(choiceScissors.checked){
                userChoice ='가위';
            }else if(choiceRock.checked){
                userChoice ='바위';
            }else{
                userChoice ='보';
          }

          aiResultText.textContent=aiChoice;

          let result; 
          if(aiChoice === '가위'){
              if(userChoice === '가위'){
                  result ='비겼습니다.';
              }else if(userChoice === '바위'){
                  result ='이겼습니다.';
                  updatePoints(amount); // 이긴 경우 베팅 금액만큼 포인트 증가
              }else{
                  result ='졌습니다.';
                  updatePoints(-amount); // 진 경우 베팅 금액만큼 포인트 감소
              }
          }else if(aiChoice === '바위'){
              if(userChoice === '가위'){
                  result ='졌습니다.';
                  updatePoints(-amount);
              }else if(userChoice === '바위'){
                  result ='비겼습니다.';
              }else{
                  result ='이겼습니다.';
                  updatePoints(amount);
              }
          }else{ // aiChoice === '보'
              if(userChoice === '가위'){
                  result ='이겼습니다.';
                  updatePoints(amount);
              }else if(userChoice === '바위'){
                  result ='졌습니다.';
                updatePoints(-amount);
              }else{
                result='비겼습니다.';
            }
          }

      userResultText.textContent=result;
      updateRemainingAttempts();

    } else {
      alert("금액을 입력해주세요.");
    }

    } else {
      alert("가위, 바위, 보 중에서 선택해주세요.");
    }
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

function updateRemainingAttempts() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_attempts.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('remaining-attempts').textContent = xhr.responseText;

            if (parseInt(xhr.responseText) <= 0) {
                document.getElementById('start-button').disabled = true;
            }
        }
    };

     var params = "username=" + encodeURIComponent("<?php echo $username; ?>");
     xhr.send(params);
}

function chargePoints() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "charge_points.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('user-point').textContent=xhr.responseText+' P';
            updateRemainingAttempts();
        }
    };


     var params ="username=" + encodeURIComponent(<?php echo json_encode($username); ?>);
     xhr.send(params);
}
</script>
