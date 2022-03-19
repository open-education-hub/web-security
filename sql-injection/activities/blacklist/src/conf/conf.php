<?php
  $servername = 'database';
  $username  = 'root';
  $password = 'root';
  $database = 'blacklist';

  // Create connection
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
      die("Unable to connect to MYSQL server");
  }
?>
