<?php
session_start();

// 세션에서 username 가져오기
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if ($username === null) {
    echo "<script>
      alert('로그인 하십시오');
      location.href = '/login/login_view.php';
    </script>";
} else {
    // username 첫번째 글자와 4번째 글자 추출
    $firstChar = substr($username, 0, 1);
    $fourthChar = substr($username, 3, 1);

    // 첫번째 글자 또는 4번째 글자에 따른 분기 처리
    if($firstChar == '4' || $fourthChar >= '4'){
        // users.json 파일 내용 불러오기
        $data = file_get_contents('../../login/users.json');
        $users = json_decode($data, true);

        foreach ($users as $key => $user) {
            if ($user['username'] == $username) {
                // username이 일치하는 사용자 정보를 'deleted user'로 변경
                $users[$key]['username'] = 'deleted user';
                $users[$key]['name'] = 'deleted user';
                $users[$key]['password'] = 'deleted user';
                $users[$key]['phone'] = 'deleted user';
                $users[$key]['point'] = 0;
                $users[$key]['value'] = 0;
                $users[$key]['value2'] = 0;
                $users[$key]['rank'] = 'deleted user';
                break;
            }
        }

        // users.json 파일에 변경된 내용 쓰기
        file_put_contents('../../login/users.json', json_encode($users));

        echo "<script>
            alert('계정이 정지되었습니다.');
            location.href = '/login/login_view.php';
        </script>";
        // 모든 세션 삭제
        session_destroy();
    }
}
?>
<?php
if (!isset($_COOKIE['noisy_web_pass'])) {
    header("Location: /index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="img/noisyit.png" type="image/x-icon"> 
<head>
    <style>
        @keyframes blink {
            0% {opacity: 0;}
            50% {opacity: 1;}
            100% {opacity: 0;}
        }

        body {
            display: flex;
            justify-content: center;
            align-items: flex-start; /* 상단부터 시작 */
            height: auto;
            margin: 0;
            background-color:black; /* 배경색 변경 */
        }

        .container {
          display:flex; 
          flex-wrap :wrap; 
          justify-content:center; 
       }

       .box {
           width :300px; /* box 크기 조절 */
           height :300px; /* box 크기 조절 */
           margin :10px; 
           border-radius :50%; 
           overflow:hidden;

           opacity:0;

       }

       .box img{
         width :100%;
         height:auto
       }

      /* 각 box에 대해 애니메이션 시작 시간 지연 설정 없음 */

    </style>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const observer = new IntersectionObserver(entries => {
          entries.forEach(entry => {
              if (entry.isIntersecting) {
                entry.target.style.animation = 'blink 4s infinite';
              } else {
                entry.target.style.animation = 'none';
              }
          });
        });

        document.querySelectorAll('.box').forEach(box => observer.observe(box));
      });
    </script>

</head>

<body>

<div class="container">
    <div class="box">
      <a href="game.php">
         <img src="https://media.discordapp.net/attachments/1170615078060568606/1170615116652347412/2c89bc0e275673fa.GIF?ex=6559af48&is=65473a48&hm=cc95ade666d59441245ffc95312f1e79dde98292999601949588e79e90461596&=" alt="GIF">
      </a>  
    </div>
      <div class="box">
      <a href="game3.php">
         <img src="https://media.discordapp.net/attachments/1170615078060568606/1170615114727174184/20231002_010255.GIF?ex=6559af48&is=65473a48&hm=4c7c6547293cd8a5e0c46463298a980d23596d7b5928803bcfce2d5f3338d69b&=" alt="GIF">
      </a>  
    </div>
        <div class="box">
    <a href="game2.php">
       <img src="https://media.discordapp.net/attachments/1170615078060568606/1170615115914158150/20231002_010218.GIF?ex=6559af48&is=65473a48&hm=5e39de1908bc0c29505db5541c29c8e5a8300c10c932fca0ef591d737a4dc95b&=" alt="GIF">
    </a>  
<!-- 대기 || 준비중입니다.
  </div>
      <div class="box">
      <a href="game4.phg">
         <img src="https://media.discordapp.net/attachments/1170615078060568606/1170615115456970822/20231002_010237.GIF?ex=6559af48&is=65473a48&hm=cc09286670fe7f295e07ae219462ef9e5c5fab381c6dc6e54185bf79e0a651d5&=" alt="GIF">
      </a>  
    </div>
      <div class="box">
      <a href="https://www.google.com">
         <img src="https://media.discordapp.net/attachments/1170615078060568606/1170615117461856337/20231002_010324.GIF?ex=6559af48&is=65473a48&hm=1067ce6be91c3c53ca4e658ab1a5564e145a6b5ebd0c281a9011cd259fc605f2&= alt="GIF">
      </a>  
      </div>
  -->
</body>
</html>
