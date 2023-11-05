<?php
session_start(); // 세션 시작

if (isset($_POST['reset'])) {
    // 모든 세션 데이터 삭제
    session_unset();

    // 세션 파괴
    session_destroy();

    // 초기화 후 다시 홈페이지로 이동
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>오류해결 센터</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<!-- 버튼 추가 -->
	<form method="post">
		<button type="submit" name="reset">초기화</button>
	</form>
</body>
</html>
