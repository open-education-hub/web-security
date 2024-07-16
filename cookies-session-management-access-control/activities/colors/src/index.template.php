<?php
	$error = '';
	$index = 0;
	$flag = '';
	if (!isset($_GET['index'])) {
		header('location: index.php?index=1');
		$error = "404 not found";
	} else {
		$index = $_GET['index'];
		if (!is_numeric($index) || $index < 0 || $index > 3141) {
			$error = "WTF MAN? 404";
		}
	}

	if ($index == 3141) {
		$flag = '__TEMPLATE__';
	} else {
		$flag = '';
	}

	if (isset($_POST['submit'])) {
		header('location: index.php?index='.($index + 1));
	}

	$color = 'rgb(' . rand(0,255) . ', ' . rand(0,255) . ', ' . rand(0,255) . ')';
?>
<body style="background: <?php echo $color; ?>">
	<?php if ($error == ''): ?>
	<form method="POST" style="text-align:center; margin-top: 200px;">
		<input type="submit" name="submit" value="Next page" style="border-radius: 20px; background: #000; color: #fff; padding: 8px 10px; font-weight: bold;" />
	</form>
	<?php else: ?>
		<?php echo '<div style="text-align: center; font-weight: bold; margin-top: 60px;">'.$error.'</div>'; ?>
	<?php endif; ?>

	<?php echo '<div style="text-align: center; font-weight: bold; margin-top: 60px;">'.$flag.'</div>'; ?>
</body>
