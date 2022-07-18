<?php

session_start();
$_SESSION['logged'] = 0;
$_SESSION['user'] = "";
$_SESSION['score'] = 0;
session_unset();
session_destroy();

header('location: index.php');
?>
