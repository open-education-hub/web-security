<?php

session_start();

$user = $_POST['username'];
$password = $_POST['password'];
$data = [];

if ($user === 'admin' && $password === 'jukxoqnnca') {
	if (isset($_POST['secret']) && $_POST['secret'] == 42) {
		$_SESSION['access'] = 'admin';
		$data['redirect'] = 'admin.php';
		echo json_encode($data);
		exit;
	}
	$_SESSION['access'] = 'staff';
	$data['redirect'] = 'staff.php';
	echo json_encode($data);
	exit;
}

echo "Invalid credentials";
?>