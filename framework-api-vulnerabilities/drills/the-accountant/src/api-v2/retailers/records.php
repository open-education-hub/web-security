<?php

if (!isset($_GET['retailer']) || !in_array($_GET['retailer'], ['emag', 'altex', 'pcgarage', 'mediagalaxy', 'flanco'])) {
	$json['error'] = "Retailer not found!";
	$data[] = $json;
	header("Content-type: application/json");
	echo json_encode($data);
	exit;
}
$retailer = $_GET['retailer'];

$json = file_get_contents('../../data/retailers/'.$retailer.'.json');
$data[] = $json;
header("Content-type: application/json");
echo json_encode($data);
exit;

?>
