<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] != 1) {
	header('location: index.php');
}

/*
$db_host = "localhost";
$db_user = "root";
$db_pass = "secure-password";

$dbhandle = mysql_connect($db_host, $db_user, $db_pass) or die("Unable to connect to MySQL");

$selected = mysql_select_db("db", $dbhandle) or die("Could not select db users");

$error = "";
$message = "";
*/
setcookie('isAdmin', 'false');

/*
if (isset($_POST['save'])) {
	if (!isset($_SESSION['logged']) || $_SESSION['logged'] != 1)
		$error = 'You are not logged in!';
	else {
		$sql = "SELECT * FROM users ORDER BY score DESC LIMIT 1";
		$result = mysql_query($sql);

		$row = mysql_fetch_array($result);
		$maxscore = $row['score'];

		$user = mysql_real_escape_string(htmlspecialchars($_POST['username']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$score = mysql_real_escape_string(htmlspecialchars($_POST['score']));
		$university = mysql_real_escape_string(htmlspecialchars($_POST['university']));
		$faculty = mysql_real_escape_string(htmlspecialchars($_POST['faculty']));
		if (!$score || $score > $maxscore + 10) $score = $_SESSION['score'];
		if (!$university) $university = $_SESSION['university'];
		if (!$faculty) $faculty = $_SESSION['faculty'];

		$sql = "UPDATE users SET username = '". $user ."', university = '". $university ."', faculty = '". $faculty ."', email = '". $email ."', score = ". $score ." WHERE id = '".$_SESSION['id']."';";
		$result = mysql_query($sql);

		if(!$result) $error = "An error ocurred. Please try again later.";
		else {
			$_SESSION['user'] = $user;
			$_SESSION['score'] = $score;
			$_SESSION['email'] = $email;
			$_SESSION['university'] = $university;
			$_SESSION['faculty'] = $faculty;
			$message = "Success! Your info has been saved!";
		}
	}
}
*/
?>
<html>
<head>
	<title>High Score</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="p-5">
<h1 class="text-center">High Score - Account</h1>
<hr />
<p class="text-center"><strong>Your current score: <?php echo $_SESSION['score']; ?></strong></p>
<p class="text-center">Username: <?php echo $_SESSION['user']; ?></p>
<p class="text-center">Email: <?php echo $_SESSION['email']; ?></p>
<p class="text-center">University: <?php echo $_SESSION['university']; ?></p>
<p class="text-center">Faculty: <?php echo $_SESSION['faculty']; ?></p>
<p class="text-center"><a class="btn btn-warning" style="color: #fff;" href="logout.php">Logout</a>&nbsp;&nbsp;<a class="btn btn-info" style="color: #fff;" href="leaderboard.php">Leaderboard</a></p>
<h3 class="text-center">Edit account</h3>
<hr />
<div style="max-width: 400px; margin: 0 auto;">
<p class="text-center success-message" style="color: green; display:none;">Succes! Ai salvat modificarile!</p>
<form method="POST">
	<input id="username" type="text" name="username" placeholder="Username" value="<?php echo $_SESSION['user']; ?>" class="form-control mb-3" />
	<input id="email" type="text" name="email" placeholder="Email" value="<?php echo $_SESSION['email']; ?>" class="form-control mb-3" />
	<button id="save" class="form-control mb-3 btn btn-success" >Save</button>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script>
function a2hex(str) {
  var arr = [];
  for (var i = 0, l = str.length; i < l; i ++) {
    var hex = Number(str.charCodeAt(i)).toString(16);
    arr.push(hex);
  }
  return arr.join('');
}
</script>
<script>
$(document).ready(function() {
	$("#save").click(function(ev)
	{
		ev.preventDefault();
		var username = $("#username").val();
		var email = $("#email").val();
		var data = 'username='+username+'&email='+email;
		data = a2hex(data);
		$.ajax({
			type: "POST",
			url: "api-save-user.php",
			data: {q: data},
			success: function(data) {
				$(".success-message").toggle();
				setTimeout(function(){ $(".success-message").toggle(); }, 1000);

				console.log(data);
			}
		});
	});
});
</script>

</body>
</html>
