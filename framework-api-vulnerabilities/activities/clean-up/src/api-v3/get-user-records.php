<?php

$json = file_get_contents('../data/users/user-information.json');
$data[] = $json;
header("Content-type: application/json");
echo json_encode($data);
exit;

?>
