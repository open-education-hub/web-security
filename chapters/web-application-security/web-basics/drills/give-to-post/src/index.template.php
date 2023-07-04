<!doctype html>
<html>
	<head>
		<title>This is the title of the webpage!</title>
	</head>
	<body>
		<?php
			$message='<p>You have to <strong>ask</strong> for the <strong>flag</strong> to <strong>post</strong> it!</p>';
			if (isset($_POST['ask']) && !empty($_POST['ask'])) {
				if ($_POST['ask'] == "flag") {
					$message='<p>__TEMPLATE__</p>';
				}
			}
			echo $message . "\n";
		?>
	</body>
</html>
