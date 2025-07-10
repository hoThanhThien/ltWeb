<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dùng cookie</title>
</head>

<body>
<?php
	if(isset($_COOKIE["abc"]))
		echo "Cookie abc có giá trị: ".$_COOKIE["abc"];
	else
		echo "Không tồn tại cookie abc";
	?>
</body>
</html>