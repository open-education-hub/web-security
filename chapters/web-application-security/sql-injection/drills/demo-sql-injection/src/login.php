<?php

$servername = 'database';
$username  = 'root';
$password = 'root';
$database = 'demo';

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Unable to connect to MYSQL server");
}

?>
