<?php

$time = isset($_GET['time']) ? $_GET['time'] : '';

$json = file_get_contents('live_core.json');
$data = json_decode($json, true);

if ($data['time'] > $time) {
  echo json_encode($data);
} else {
  echo 'null';
}

?>
