<?php
session_start();
  
  if (isset($_SESSION['username'])) {
     if(isset($_POST['checkin'])){
      // 오늘 날짜 계산
      $today = date('Ymd');
  
      // checkin.json 파일 읽기
      $checkinData = json_decode(file_get_contents('checkin.json'), true);
  
      // 해당 사용자의 출석체크 여부 확인
      if (isset($checkinData[$today][$_SESSION['username']])) {
        // 이미 출석체크를 한 경우
        echo "<script>alert('오늘은 이미 출석체크를 하셨습니다.');</script>";
      } else {
      // users.json 파일 읽기
      $userList = json_decode(file_get_contents('../login/users.json'), true);
  
      
      // 해당 사용자 정보 찾기
      $foundUser = null;
      foreach ($userList as $user) {
        if ($user['username'] === $_SESSION['username']) {
          $foundUser = $user;
          break;
        }
      }
      
      // 사용자 정보가 있을 경우 포인트 증가시키기
      if ($foundUser !== null) {
        $foundUser['point'] += 1000;
        $userData = json_encode($userList);
        file_put_contents('../login/users.json', $userData);
      }
      
          
  
        // checkin.json 파일 업데이트
        $checkinData[$today][$_SESSION['username']] = true;
        file_put_contents('checkin.json', json_encode($checkinData));
  
        // 출석체크 완료 메시지 출력
        echo "<script>alert('출석체크 완료! 1000원을 획득하셨습니다.');</script>";
      }    
    }
  }
  // 페이지 이동
  echo "<script>location.href='comunity.php';</script>";
  
  if (!isset($_COOKIE['noisy_web_pass'])) {
      header("Location: /index.php");
      exit;
  }
  ?>