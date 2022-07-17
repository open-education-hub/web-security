<?php
$flag = '__TEMPLATE__';

if (isset($_GET['random_numberrr']) && intval($_GET['random_numberrr']) === 99) {
	echo $flag;
} else {
	echo 'Nothing to see here';
}
?>
