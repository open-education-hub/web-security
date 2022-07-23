<?php
session_start();

$db_host = "mysql";
$db_user = "rootsss";
$db_pass = "secure-password";

$dbhandle = mysql_connect($db_host, $db_user, $db_pass, 9906) or die("Unable to connect to MySQL");

$selected = mysql_select_db("db", $dbhandle) or die("Could not select db users");

$error_1 = "";
$error_2 = "";
$message = "";

if (isset($_POST['login'])) {
	if (isset($_SESSION['logged']) && $_SESSION['logged'] == 1)
		$error_1 = 'You are already logged in';
	else {
		$user = mysql_real_escape_string(htmlspecialchars($_POST['username']));
		$pass = mysql_real_escape_string(htmlspecialchars($_POST['password']));
		$sql = "SELECT * FROM users WHERE username = '".$user."' AND password = '" . md5($pass) . "';";
		$result = mysql_query($sql);

		$row = mysql_fetch_array($result);
		if(!$row) $error_1 = "Incorrect username or password.";
		else {
			$_SESSION['id'] = $row['id'];
			$_SESSION['logged'] = 1;
			$_SESSION['user'] = $user;
			$_SESSION['score'] = $row['score'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['university'] = $row['university'];
			$_SESSION['faculty'] = $row['faculty'];
			header("location: account.php");
		}
	}
}

if (isset($_POST['register'])) {
	if (isset($_SESSION['logged']) && $_SESSION['logged'] == 1)
		$error_2 = 'You are already logged in';
	else {
		$user = mysql_real_escape_string(htmlspecialchars($_POST['username']));
		$pass = mysql_real_escape_string(htmlspecialchars($_POST['password']));
		$faculty = mysql_real_escape_string(htmlspecialchars($_POST['faculty']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$university = mysql_real_escape_string(htmlspecialchars($_POST['university']));
		if (strlen($user) < 3 || strlen($pass) < 3)
			$error_2 = 'Username and password must be at least 3 characters!';
		else {
			$sql = "INSERT INTO users (username, password, score, university, faculty, email) VALUES ('".$user."', '" . md5($pass) . "', 0, '".$university."', '".$faculty."', '".$email."');";
			$result = mysql_query($sql);
			if (!$result) $error_2 = mysql_error(); //"There was an unexpected error. Try again later.";
			else $message = 'Success! You can now log in using the above form!';
		}
	}
}

?>
<html>
<head>
	<title>High Score</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="p-5">
<h1 class="text-center">High Score - Login & Register</h1>

<hr />

<h3 class="text-center">Login</h3>

<div style="max-width: 400px; margin: 0 auto;">
<p class="text-center" style="color: red;"><?php echo $error_1; ?></p>
<form method="POST">
	<input type="text" name="username" placeholder="Username" class="form-control mb-3" />
	<input type="password" name="password" placeholder="Password" class="form-control mb-3" />
	<input type="submit" name="login" value="Login" class="form-control mb-3 btn btn-success" />
</form>
</div>

<hr />

<h4 class="text-center">OR</h4>

<h3 class="text-center">Register</h3>

<div style="max-width: 400px; margin: 0 auto;">
<p class="text-center"><?php echo $message; ?></p>
<p class="text-center" style="color: red;"><?php echo $error_2; ?></p>
<form method="POST">
	<input type="text" name="username" placeholder="Username" class="form-control mb-3" />
	<input type="password" name="password" placeholder="Password" class="form-control mb-3" />
	<input type="text" name="email" placeholder="Email" class="form-control mb-3" />
	<input type="text" name="university" placeholder="University" class="form-control mb-3" />
	<input type="text" name="faculty" placeholder="Faculty" class="form-control mb-3" />
	<input type="submit" name="register" value="Register" class="form-control mb-3 btn btn-info" />

</form>
</div>

<hr />

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
