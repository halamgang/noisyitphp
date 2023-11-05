<?php
session_start();

if (isset($_GET['session'])) {
    if ($_GET['session'] == 'dev') {
        $_SESSION['username'] = '21101';
        $_SESSION['rank'] = 'dev';
    } elseif ($_GET['session'] == 'user') {
        $_SESSION['username'] = '10701';
        $_SESSION['rank'] = 'user';
    }
}
?>
