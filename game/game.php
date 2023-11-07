<?php
session_start();
$remainingAttempts = 0;
$remainingAttempts = 0; // 또는 다른 기본값
$string = file_get_contents("../login/users.json");

if ($string === false) {
    die('웹서버를불러오는도중실패했습니다.문의하세요"');
}

$json_a = json_decode($string, true);

if ($json_a === null) {
    die('웹서버를불러오는도중실패했습니다.문의하세요');
}

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $username = "미가입자";
}

$string = file_get_contents("../login/users.json");
$json_a = json_decode($string, true);

$point = "0";
$remainingAttempts = 0;

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

        $user['point'] = strval($point);

        break;
    }
}

if ($remainingAttempts <= 0) {
    $disableStartButton = true;
} else {
    $disableStartButton = false;
}

$_SESSION["point"] = $point;
$_SESSION["remainingAttempts"] = $remainingAttempts;

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
          <form method="POST" action="" onsubmit="event.preventDefault(); startGame();">
          <button type="submit" id="start-button" <?php if ($disableStartButton) echo "disabled"; ?>>시작하기</button>
          </form>

         </div>

<div id="result-container">
    <?php
        echo "<p class='small-text'>";
        echo "<p style='color:#1ECD97;'>AI_결과: <span id='ai-result'></span></p>";
        echo "<p style='color:#1ECD97;'>사용자_결과: <span id='user-result'></span></p>";
        echo "<p style='color:#1ECD97;'>사용자_포인트: <span id='user-point'>" . $_SESSION["point"] . " P</span> (<span id='user-point-change'></span>)</p>";    
        echo "<p style='color:#1ECD97;'>남은 횟수: <span id='remaining-attempts'>" . $_SESSION["remainingAttempts"] . "</span> | <a href='#' onclick=\"chargePoints()\" style='color:#1ECD97;'>충전하기</a></p>";
        echo "<p style='color:#1ECD97;'>충전요금 1000포인트 | 하루 2회 무료</p>";
    ?>
</div>

<script>
var userPoint = parseInt("<?php echo $_SESSION["point"]; ?>");
var remainingAttempts = parseInt("<?php echo $_SESSION["remainingAttempts"]; ?>");

function startGame() {
    var choiceOdd = document.getElementById("choice-input-odd");
    var choiceEven = document.getElementById("choice-input-even");
    var amountInput = document.getElementById("amount-input");
    var aiResultText = document.getElementById("ai-result");
    var userResultText = document.getElementById("user-result");

    if (choiceOdd.checked || choiceEven.checked) {
        if (amountInput.value !== "") {
            var amount = parseInt(amountInput.value);

            if (amount > userPoint) {
                alert('가지고 있는 포인트보다 많은 금액을 베팅할 수 없습니다.');
                return;
            }

            aiResultText.textContent= "";
            userResultText.textContent= "";

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

            aiResultText.textContent=aiResult;

            if(aiResult === userChoice){
                userResultText.textContent="맞습니다.";
                updatePoints(amount * 1.3);
            } else {
                userResultText.textContent="틀렸습니다.";
                updatePoints(-amount);
            }

            updateRemainingAttempts();

        } else {
            alert("금액을 입력해주세요.");
        }
    } else {
        alert("홀 또는 짝을 선택해주세요.");
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

</body>
</html>