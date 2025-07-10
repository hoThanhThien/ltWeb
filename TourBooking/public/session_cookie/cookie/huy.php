<?php
setcookie("abc","",time()-1); //Hủy 1 cookie
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Hủy cookie</title>
</head>

<body>
<?php
	$a=123;
	echo md5($a);
	?>
</body>
</html>