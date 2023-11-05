<?php
session_start();

if(isset($_POST["username"]) && isset($_POST["delta"])){
    $username = $_POST["username"];
    $delta = intval($_POST["delta"]);

    // JSON 파일 로드 및 디코딩
    $string = file_get_contents("../login/users.json");
    $json_a = json_decode($string, true);

    foreach ($json_a as &$user) { 
        if ($user['username'] == $username) { 
            // 유저의 point 값에 delta 더하기
            $user['point'] += $delta;
            
            if ($user['point'] < 0) { // 포인트가 음수일 경우 0으로 설정
                $user['point'] = 0;
            }
            
            break;
        }
    }

     // users.json 파일에 업데이트된 정보 저장
     file_put_contents("../login/users.json", json_encode($json_a, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

	// 업데이트된 point 반환
	foreach ($json_a as &$user) { 
	    if ($user['username'] == $username) { 
	        echo strval($user['point']);
	        break;
	    }
	}
} else {
}
?>
