<!doctype html>
<html>
	<head>
		<title>This is the title of the webpage!</title>
	</head>
	<body>
		<?php
			$message='<p>I only answer to King-Kong!</p>';
			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				if ($_SERVER['HTTP_USER_AGENT'] == 'King-Kong') {
					$message='<p>__TEMPLATE__</p>\n';
				}
			}
			echo $message . "\n";
		?>
	</body>
</html>
