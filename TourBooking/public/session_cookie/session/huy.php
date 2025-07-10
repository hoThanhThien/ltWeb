<?php
session_start();
//Hủy tất cả session:
//session_destroy();
//Hủy 1 đối tượng:
unset($_SESSION["test1"]);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Hủy Session</title>
</head>

<body>
	<h1>Hủy session</h1>
</body>
</html>