<?php
session_start(); //kích hoạt session
//Tạo 1 session:
$_SESSION["test"]="Hello abc!";
$_SESSION["test1"]=123;

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tạo Session</title>
</head>

<body>
<?php
	echo "Session id: ".session_id();
	echo "<br/>";
	echo $_SESSION["test"];
	echo "<br/>";
	echo $_SESSION["test1"]=123;
	?>
</body>
</html>