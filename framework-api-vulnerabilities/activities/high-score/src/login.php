<html>
  <head>
    <title>login.php</title>
  </head>
  <body>
<?php

require_once 'db.php';

$query = "SELECT * from users WHERE name='" . $_POST["username"] ."' AND password='" . $_POST["password"] ."'";

$result = mysqli_query($dbhandle, $query);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
if( !$row ) echo "<p>User/password combination not found!</p>";
else {
	echo "<p>User: " . $row{'name'}. "</p>";
	echo "<p>Secret: " . $row{'secret'} . "</p>";
}
?>

  </body>
</html>
