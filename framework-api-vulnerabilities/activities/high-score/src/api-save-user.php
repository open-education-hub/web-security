<?php
session_start();

$db_host = "mysql";
$db_user = "rootsss";
$db_pass = "secure-password";

$dbhandle = mysql_connect($db_host, $db_user, $db_pass) or die("Unable to connect to MySQL");

$selected = mysql_select_db("db", $dbhandle) or die("Could not select db users");


if (!isset($_SESSION['logged']) || $_SESSION['logged'] != 1)
	$error = 'You are not logged in!';
else {
	$sql = "SELECT * FROM users ORDER BY score DESC LIMIT 1";
	$result = mysql_query($sql);

	$row = mysql_fetch_array($result);
	$maxscore = $row['score'];

	$hex = $_POST['q'];
	$string = ''; // hex to ascii
    for ($i = 0; $i < strlen($hex) - 1; $i += 2){
        $string .= chr(hexdec($hex[$i].$hex[$i + 1]));
    }
    $string = str_replace(' ', '', $string);


	$pairs = explode('&', $string);

	foreach($pairs as $pair) {
		$pieces = explode('=', $pair);
		if ($pieces[0] == 'email')
			$email = mysql_real_escape_string(htmlspecialchars($pieces[1]));
		if ($pieces[0] == 'username')
			$user = mysql_real_escape_string(htmlspecialchars($pieces[1]));
		if ($pieces[0] == 'score')
			$score = mysql_real_escape_string(htmlspecialchars($pieces[1]));
		if ($pieces[0] == 'faculty')
			$faculty = mysql_real_escape_string(htmlspecialchars($pieces[1]));
		if ($pieces[0] == 'university')
			$university = mysql_real_escape_string(htmlspecialchars($pieces[1]));
	}

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
		$json = $message;
		$data[] = $json;
		header("Content-type: application/json");
		echo json_encode($data);
		exit;
	}

}
