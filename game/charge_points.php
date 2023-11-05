<?php 
session_start();

$username = $_POST["username"];

$string = file_get_contents("../login/users.json");
$json_a = json_decode($string, true);

$point;
$remainingAttempts;

foreach ($json_a as &$user) {
    if ($user['username'] == $username) {
        $point = intval($user['point']);
        if (isset($user['value2'])) {
            $remainingAttempts = intval($user['value2']);
        }

        // 충전 비용이 1000P 이상일 경우만 처리
        if ($point >= 1000) { 
            $point -= 1000; // 포인트 감소
            $remainingAttempts += 3; // 남은 시도 횟수 증가

            $user['point'] = strval($point); 
            $user['value2'] = strval($remainingAttempts);
            
            break;
        } else { 
           echo '포인트가 부족합니다..'; 
           return; 
       }
    }
}

file_put_contents("../login/users.json", json_encode($json_a, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo $point;
?>
