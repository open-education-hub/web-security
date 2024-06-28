<?php
			session_start();
			$flag = "__TEMPLATE__";
			if (isset($_SESSION['time'])) {
				if ($_SESSION['time'] == time()) {
					echo "<p>" . $flag . "</p>\n";
				}
			}
?>

<!doctype html>
<html>
	<head>
		<title>This is the title of the webpage!</title>
	</head>
	<body>
	</body>
</html>
