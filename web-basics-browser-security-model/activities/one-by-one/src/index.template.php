<?php
	session_start();
	$flag = '__TEMPLATE__';
	if (!isset($_SESSION['count'])) {
		$_SESSION['count'] = 0;
	} else {
		$_SESSION['count'] = ($_SESSION['count'] + 1) % strlen($flag);
	}
	echo "<p>" . $flag[$_SESSION['count']] . "</p>\n";
?>

<!doctype html>
<html>
	<head>
		<title>This is the title of the webpage!</title>
	</head>
	<body>
	</body>
</html>
