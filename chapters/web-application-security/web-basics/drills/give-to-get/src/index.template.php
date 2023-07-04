<!doctype html>
<html>
	<head>
		<title>This is the title of the webpage!</title>
	</head>
	<body>
		<?php
			$message='<p>You have to <strong>ask</strong> for the <strong>flag</strong> to <strong>get</strong> it!</p>';
			if (isset($_GET['ask']) && !empty($_GET['ask'])) {
				if ($_GET['ask'] == "flag") {
					$message='<p>__TEMPLATE__</p>';
				}
			}
			echo $message;
		?>
	</body>
</html>
