<?php

$flag = '__TEMPLATE__';
$error = '';

if (isset($_POST['submit'])) {
	if (isset($_POST['username']) && isset($_POST['password'])) {
		if ($_POST['username'] == $_POST['password']) {
			$error = 'Your password can not be your username!';
		} else if (hash('sha256', $_POST['username']) === hash('sha256', $_POST['password'])) {
			die($flag);
		} else {
			$error = 'Invalid credentials!';
		}
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
					<?php if ($error != ''): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $error; ?>
						</div>
					<?php endif; ?>
					<form method="POST">
						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" name="username" class="form-control" id="username">
							<!-- TODO: Remove source.phar -->
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
	</body>
</html>
