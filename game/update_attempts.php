<?php 
session_start();

$username = $_POST["username"];

$string = file_get_contents("../login/users.json");
$json_a = json_decode($string, true);

$remainingAttempts = 0;

foreach ($json_a as &$user) {
    if ($user['username'] == $username) {
        if (isset($user['value2'])) {
            $remainingAttempts = intval($user['value2']);
            if ($remainingAttempts > 0) {
                $remainingAttempts--;
                $user['value2'] = strval($remainingAttempts);
            }
        }
        break;
    }
}

file_put_contents("../login/users.json", json_encode($json_a, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo $remainingAttempts;
?>
