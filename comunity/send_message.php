<?php
session_start();

function encrypt($plaintext, $key, $iv) {
    return base64_encode(openssl_encrypt($plaintext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
}

function decrypt($ciphertext, $key, $iv) {
    return openssl_decrypt(base64_decode($ciphertext), 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
}

if (isset($_POST['message'])) {
    $is_anonymous = isset($_POST['is_anonymous']) && $_POST['is_anonymous'] === 'on';
    $username = $is_anonymous ? 'Anonymous' : $_SESSION['username'];
    $timestamp = date('Y-m-d H:i:s');
    $message = $_POST['message'];
    
    $key = "your-16-byte-key"; // 16 byte secret key
    $iv = "your-16-byte-iv"; // 16 byte initialization vector

    $encrypted_username = encrypt($username, $key, $iv);
    $encrypted_rank = encrypt('user', $key, $iv);

    if (!file_exists('posts.json')) {
        file_put_contents('posts.json', json_encode(array()));
    }
    
    $posts_data = json_decode(file_get_contents('posts.json'), true);
  
    $new_post = array(
        'username' => $encrypted_username,
        'timestamp' => $timestamp,
        'message' => $message,
        'rank' => $encrypted_rank,
    );
    
    $posts_data[] = $new_post;

    file_put_contents('posts.json', json_encode($posts_data));
    
} else {
    // 메시지를 전송할 수 없는 경우 처리 로직
}
?>
