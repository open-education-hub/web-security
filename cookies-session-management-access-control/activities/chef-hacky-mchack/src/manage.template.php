<?php
if (isset($_COOKIE["u"]) && $_COOKIE["u"] === 'hacky mchack') {
	echo $flag = '<div style="opacity: 0.05">__TEMPLATE__</div>';
}
?>