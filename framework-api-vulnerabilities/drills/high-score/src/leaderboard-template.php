<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged'] != 1) {
	header('location: index.php');
}

$db_host = "mysql";
$db_user = "rootsss";
$db_pass = "secure-password";

$dbhandle = mysql_connect($db_host, $db_user, $db_pass) or die("Unable to connect to MySQL");

$selected = mysql_select_db("db", $dbhandle) or die("Could not select db users");


$sql = "SELECT username, score FROM users ORDER BY score DESC LIMIT 5;";
$result = mysql_query($sql);
$results = array();

$i = 0;
while($row = mysql_fetch_assoc($result)) {
	$results[$i]['username'] = $row['username'];
	$results[$i]['score'] = $row['score'];
	$i++;
}

$sql = "SELECT * FROM users WHERE id = ".$_SESSION['id']." LIMIT 1";
$result = mysql_query($sql);

$row = mysql_fetch_array($result);
$myscore = $row['score'];

$sql = "SELECT * FROM users WHERE id != ".$_SESSION['id']." ORDER BY score DESC LIMIT 1";
$result = mysql_query($sql);

$row = mysql_fetch_array($result);
$maxscore = $row['score'];

$flag = '';

if (isset($_COOKIE['isAdmin']) && $_COOKIE['isAdmin'] == 'true' && $myscore > $maxscore) {
	$flag = '__TEMPLATE__';
}

?>
<html>
<head>
	<title>High Score</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="p-5">
<h1 class="text-center mb-5">High Score - Top 5</h1>
<hr />
<?php if($flag != ''): ?>
	<p class="text-center font-weight-bold">Your prize: <?php echo $flag; ?> Congratulations!</p>
	<hr />
<?php endif; ?>
<div style="max-width: 200px; margin: 0 auto;">

	<ol>
		<?php foreach($results as $result): ?>
			<li><?php echo $result['username']; ?> - <?php echo $result['score']; ?> points</li>
		<?php endforeach; ?>
	</ol>

</div>

<hr />

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
