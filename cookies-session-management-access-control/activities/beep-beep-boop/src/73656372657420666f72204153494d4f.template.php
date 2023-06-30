<?php
setcookie('robotType', 'HUMAN'); // 48756d616e

if ($_COOKIE['robotType'] == 'ASIMOV') { // 4153494d4f
	echo 'Congrats! You’ve proven to be worthy. <br />Here is your secret: __TEMPLATE__';
} else {
	echo 'This is a secure area that can only be accessed by the most advanced humanoid robots.';
}
?>