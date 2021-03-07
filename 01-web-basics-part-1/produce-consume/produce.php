<!doctype html>
<html>
	<head>
		<title>This is the title of the webpage!</title>
	</head>
	<body>
		<?php
			session_start();
			$time = time();
			echo "<p>" . $time . "</p>\n";
			$_SESSION['time'] = $time;
		?>
	</body>
</html>
