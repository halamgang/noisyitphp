<?php
session_start();

if ($_SESSION['username'] != '11011') {
    echo "<script>alert('접근할 수 없습니다.'); history.back();</script>";
    exit;
}
?>
  
  <!DOCTYPE html>
  <html>
  <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript">
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
        window.onbeforeunload = function() { return "떠날수 없어요."; };
    </script>
  </head>
  <body>
    <link href="game.css" rel="stylesheet" />
      <div class="team-box" data-team="">
            <div id="result-container">
            <?php
                echo "<p class='game-title'>안내 | 계정 정지</p>";
            ?>
            </div>

            <div class="line"></div>
         <div class="input-container">
           <label for="choice-input">정지 ID | 11011</label>
         </div>
           <div class="input-container">
             <label for="choice-input">정지전 포인트 | 1.008E+19P</label>
           </div>
         <div class="input-container">
           <label for="choice-input">정지후 포인트 | 0P</label>
         </div>
           <div class = "input-container">
                <input type = "text" id = "u_r_ban" placeholder = "이의 제기">
            <form method="POST" action="" onsubmit="event.preventDefault(); startGame();">
            <button type="submit" id="start-button">전송</button>
            </form>

           </div>

  <div id="result-container">
      <?php
          echo "<p class='small-text'>";
          echo "<p style='color:#1ECD97;'>정지 이유:</p>";
          echo "<p style='color:#1ECD97;'>비정상 적인 포인트</p>";
          echo "<p style='color:#1ECD97;'>정지 기간 : 3일+ </p>";
          echo "<p style='color:#1ECD97;'>정지 해제 : 2023년11월13일 이후</p>";
          echo "<p style='color:#1ECD97;'>정지 부분 : 일부 이용 정지</p>";
      ?>
  </div>
      </div>   
  </body>
  </html>
