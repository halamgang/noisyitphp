<?php
session_start();
$remainingAttempts = 0;
$remainingAttempts = 0; // 또는 다른 기본값
$string = file_get_contents("../login/users.json");

if ($string === false) {
    // 파일 읽기 실패: 오류 메시지 출력 또는 적절한 처리 수행
    die('웹서버를불러오는도중실패했습니다.문의하세요"');
}

$json_a = json_decode($string, true);

if ($json_a === null) {
    // JSON 디코딩 실패: 오류 메시지 출력 또는 적절한 처리 수행
    die('웹서버를불러오는도중실패했습니다.문의하세요');
}
// username 값을 세션에서 가져오기
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $username = "미가입자";
}

// JSON 파일 로드 및 디코딩
$string = file_get_contents("../login/users.json");
$json_a = json_decode($string, true);

$point = "0"; // 초기값 설정

foreach ($json_a as &$user) { // 모든 유저를 순회하며 
    if ($user['username'] == $username) { // username이 일치하는 유저를 찾으면 
        $point = $user['point']; // 그 유저의 point 값 저장 
        if (isset($user['value2'])) {
            $remainingAttempts = intval($user['value2']); // value2 값을 가져와서 정수로 변환하여 남은 횟수로 설정
            if ($remainingAttempts > 0) {
                $remainingAttempts--; // 남은 횟수 감소
                $user['value2'] = strval($remainingAttempts); // value2 값을 업데이트
            }
        }

        // 해당 부분 추가: 사용자 포인트 업데이트
        $user['point'] = strval($point);

        break;
    }
}

if ($remainingAttempts <= 0) {
    echo "<script>document.getElementById('start-button').disabled = true;</script>";
}
// users.json 파일에 업데이트된 정보 저장
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
              echo "<p class='game-title'>랜덤의! 홀짝 게임! 베팅하자!</p>";
              echo "<p class='small-text'>";
                echo "<p style='color:#1ECD97;'>오류시 <a href='' onclick='window.location.reload();' style='color:#1ECD97;'>새로고침(클릭)</a> 해주세요!</p>";
              echo "<span style='color:#1ECD97;'>$username 님 $point P 보유중</span>";
              echo "</p>";
          ?>
          </div>

          <div class="line"></div>

         <div class="input-container">
           <label for="choice-input">홀</label>
           <input type="radio" id="choice-input-odd" name="choice" value="홀">

           <label for="choice-input">짝</label> 
           <input type="radio" id="choice-input-even" name="choice" value ="짝">
         </div>

         <div class = "input-container">
              <input type = "text" id = "amount-input" placeholder = "금액 입력">
              <!-- 추가된 부분 - form 태그로 감싸고 POST 방식으로 데이터 전송 -->
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
    var choiceOdd = document.getElementById("choice-input-odd");
    var choiceEven = document.getElementById("choice-input-even");
    var amountInput = document.getElementById("amount-input");
    var aiResultText = document.getElementById("ai-result");
    var userResultText = document.getElementById("user-result");

    if (choiceOdd.checked || choiceEven.checked) {
        if (amountInput.value !== "") {
            // 사용자가 입력한 베팅 금액
            var amount = parseInt(amountInput.value);

            // 사용자의 현재 포인트
            var userPointElement = document.getElementById('user-point');
            var userPoint = parseInt(userPointElement.textContent);

            if (amount > userPoint) { // 베팅 금액이 사용자의 포인트보다 큰 경우
                alert('가지고 있는 포인트보다 많은 금액을 베팅할 수 없습니다.');
                return;
            }

      aiResultText.textContent= ""; // 결과 초기화
      userResultText.textContent= ""; // 결과 초기화

      var randomNum = Math.floor(Math.random() * 10) + 1;

      var aiResult;
      if (randomNum % 2 === 0) {
        aiResult = "짝";
      } else {
        aiResult = "홀";
      }

      var userChoice;
      if (choiceOdd.checked) {
        userChoice = "홀";
      } else {
        userChoice ="짝";
      }

      var amount = parseInt(amountInput.value);

      aiResultText.textContent=aiResult;

      if(aiResult === userChoice){
        userResultText.textContent="맞습니다.";
        updatePoints(amount * 1.3); // 맞추면 입력금액의 1.3배 적립
      }else{
        userResultText.textContent="틀렸습니다.";
        updatePoints(-amount); // 틀리면 입력금액만큼 차감
      }

          updateRemainingAttempts();

        } else {
          alert("금액을 입력해주세요.");
      }

      // 결과를 보여준 후 삭제하는 부분 제거

    } else {
      alert("홀 또는 짝을 선택해주세요.");
  }
}

function updatePoints(delta) { 
     // delta만큼 point 변경 요청 보내기
     var xhrPointUpdateRequest= new XMLHttpRequest();
     xhrPointUpdateRequest.open("POST", "update_points.php", true);
     xhrPointUpdateRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

     xhrPointUpdateRequest.onreadystatechange = function () {
         if (xhrPointUpdateRequest.readyState === 4 && xhrPointUpdateRequest.status === 200) {
             document.getElementById('user-point').textContent=xhrPointUpdateRequest.responseText + ' P';

             // 포인트 변동량 업데이트 및 색상 설정
             var pointChangeElement=document.getElementById('user-point-change');
             pointChangeElement.textContent=(delta >0 ? '+':'')+delta+' P';

             if(delta>0){
                 pointChangeElement.style.color='blue';
             }else{
                 pointChangeElement.style.color='red';
             }
         }
     };

// 현재 사용자 이름과 변동할 point 양을 서버에 보냅니다.
var params ="username=" + encodeURIComponent(<?php echo json_encode($username); ?>)+"&delta="+delta;

      xhrPointUpdateRequest.send(params);
}

function updateRemainingAttempts() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_attempts.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        console.log('처리수량: ' + xhr.readyState); // 상태 출력
        console.log('200 = 안전처리: ' + xhr.status); // HTTP 상태 코드 출력

        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('remaining-attempts').textContent = xhr.responseText;

            // 남은 시도 횟수가 없으면 시작하기 버튼 비활성화
            if (parseInt(xhr.responseText) <= 0) {
                document.getElementById('start-button').disabled = true;
            }
        }
    };

     // 현재 사용자 이름을 서버에 보냅니다.
     var params = "username=" + encodeURIComponent("<?php echo $username; ?>");
     console.log('서버에 전송한 아이디 / 관리자페이지에서 감시 ' + params); // 요청 파라미터 출력
     xhr.send(params);
}


function chargePoints() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "charge_points.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('user-point').textContent=xhr.responseText+' P';
            updateRemainingAttempts(); // 포인트 충전 후 남은 시도 횟수 업데이트
        }
    };


     // 현재 사용자 이름을 서버에 보냅니다.
     var params ="username=" + encodeURIComponent(<?php echo json_encode($username); ?>);
     xhr.send(params);
}
</script>

<!-- JavaScript 코드 -->
</body>
</html>
