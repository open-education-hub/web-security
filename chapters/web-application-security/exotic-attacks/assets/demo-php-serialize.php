<?php
    class PHPClass
    {
        public $evil_command = "system('whoami');";
        private $random_number = 9;
        private $arr = [1, 5];

        function __wakeup() {
            if (isset($this->evil_command)) {
                eval($this->evil_command);
            }
        }

        function __toString() {
            return "called from toString method";
        }
    }

    echo "To string: ".(new PHPClass)."\n\n";

    $serialized = serialize(new PHPClass);
    echo "Serialized: ".$serialized."\n";
    // Uncomment for pure evilness
    // unserialize($serialized);
?>
