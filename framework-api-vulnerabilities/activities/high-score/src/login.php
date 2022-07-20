<html>
  <head>
    <title>login.php</title>
  </head>
  <body>
<?php

$hostname="localhost";
$username="rootsss";
$password="uz6geeX5ahph5ya7";

$dbhandle=mysql_connect($hostname, $username, $password) or die("Unable to connect to MySQL");

$selected = mysql_select_db("db", $dbhandle) or die("Could not select db users");

$query = "SELECT * from users WHERE name='" . $_POST["username"] ."' AND password='" . $_POST["password"] ."'";

$result = mysql_query( $query );
$row = mysql_fetch_array($result);
if( !$row ) echo "<p>User/password combination not found!</p>";
else {
	echo "<p>User: " . $row{'name'}. "</p>";
	echo "<p>Secret: " . $row{'secret'} . "</p>";
}
?>

  </body>
</html>
