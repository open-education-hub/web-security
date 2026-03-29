<?php
  $servername = 'database';
  $username  = 'root';
  $password = 'root';
  $database = 'blacklist';

  $conn = new mysqli($servername, $username, $password, $database);
  if ($conn->connect_error) {
    die("Unable to connect to MYSQL server");
  }
?>
