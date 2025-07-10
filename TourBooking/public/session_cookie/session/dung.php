<?php
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dùng Session</title>
</head>

<body>
<?php
	if(isset($_SESSION["test"]))
		echo "Session test có giá trị: ".$_SESSION["test"];
	else
		echo "Không tồn tại session";
	if(isset($_SESSION["test1"]))
	{
		echo "<br/>Session test1 ban đầu có giá trị: ".$_SESSION["test1"];
		$_SESSION["test1"]=$_SESSION["test1"]*10;
		echo "<br/>Session test1 đã thực hiện công thức có giá trị: ".$_SESSION["test1"];
	}
		
	else
		echo "Không tồn tại session";
	?>
</body>
</html>