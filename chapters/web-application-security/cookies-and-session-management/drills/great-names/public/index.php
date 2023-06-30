<?php
session_start();

setcookie("marco", "?", time() + 86400);

if (!isset($_SESSION['step'])) {
	$_SESSION['step'] = 0;
} else {
	if (isset($_COOKIE['marco']) && $_COOKIE['marco'] === 'polo') {
		$_SESSION['step'] = 1;
		setcookie("fernando", "?");
	}
	if (isset($_COOKIE['fernando']) && $_COOKIE['fernando'] === 'magellan') {
		$_SESSION['step'] = 2;
		setcookie("cristofor", "?");
	}
	if (isset($_COOKIE['cristofor']) && $_COOKIE['cristofor'] === 'columb') {
		$_SESSION['step'] = 3;
		setcookie("GG", "Now look closer in the document body");
	} 
}

if ($_SESSION['step'] == 3) {
	echo '<!--SSS{y0u_f0und_th3_gr3at3st_3xpl0r3rs}-->';
}
?>
