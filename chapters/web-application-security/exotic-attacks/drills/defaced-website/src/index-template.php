<?php

$flag = '__TEMPLATE__';
$sec_pass = '0e413229387827631581229643338212';
$error = '';
$message = '';

if (isset($_POST['username']) && isset($_POST['password'])) {
	if (md5($_POST['password'] . $_POST['username']) == $sec_pass) {
		$message = $flag;
	} else {
		$error = 'You will never get your site back, haha!';
	}
}
?>

<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	</head>
	<body>
		<section>
			<div class="card mt-5" style="width: 28rem; margin: auto;">
				<div class="card-body">
					<img src="img/d3f4c3d.png" style="height:1px;" />
					<h1 class="mb-4">Defaced website :(</h1>
					<?php if ($error != ''): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $error; ?>
						</div>
					<?php endif; ?>
					<form method="POST">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" name="username" class="form-control" id="username">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" name="password" class="form-control" id="password">
						</div>
						<input type="submit" class="btn btn-primary" name="submit" value="Login" />
					</form>
				</div>
			</div>
		</section>
		<?php if ($message != ''): ?>
			<div class="alert alert-info mt-5 text-center" role="alert">
				<?php echo $message; ?>
			</div>
		<?php endif; ?>
	</body>
</html>
