<?php
$db_host = "mysql";
$db_user = "sss_user";
$db_pass = "secure-password";
$db_name = "db";

$dbhandle = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$dbhandle) {
    die("Database connection failed: " . mysqli_connect_error());
}