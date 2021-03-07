<!doctype html>
<html>
	<head>
		<title>This is the title of the webpage!</title>
	</head>
	<body>
		<?php
session_start();
$flag = "SSS_CTF{...}"; // Fight for your flag.
if (isset($_SESSION['time'])) {
	if ($_SESSION['time'] == time()) {
		echo "<p>" . $flag . "</p>\n";
	}
}
		?>
	</body>
</html>
