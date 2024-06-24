<?php
if (isset($_GET['planet']) && $_GET['planet'] != '' && file_exists($_GET['planet']))
	include($_GET['planet']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Solar System</title>
		
		<style>
		body {
			background: #000;
		}
		ul li {
			list-style-type: none;
		}
		ul li {
			color: #fff;
			display: inline-block;
			font-size: 20px;
			padding: 20px;
		}
		ul {
			width: 830px;
			margin: auto;
			padding: 50px 0;
		}
		#particles-js {
			position: fixed;
			width: 100%;
			height: 100%;
			z-index: -5;
		}
		</style>
    </head>
	<body>
		<div id="particles-js"></div>
		<ul>
			<a href="?planet=mercury/mercury.php"><li>Mercury</li></a>
			<a href="?planet=venus.php"><li>Venus</li></a>
			<a href="?planet=earth/earth.php"><li>Earth</li></a>
			<a href="?planet=mars/mars.php"><li>Mars</li></a>
			<a href="?planet=jupiter.php"><li>Jupiter</li></a>
			<a href="?planet=saturn.php"><li>Saturn</li></a>
			<a href="?planet=uranus.php"><li>Uranus</li></a>
			<a href="?planet=neptune.php"><li>Neptune</li></a>
		</ul>

		<script src="particles/particles.js"></script>
		<script src="particles/app.js"></script>
		<!--<script>var _0x5c09=['dot-php','earth\x20','log','slash\x20','dot-dot-slash\x20','flag\x20','NASA\x20'];(function(_0xe916b7,_0x5c0933){var _0x34f1b0=function(_0x4a989c){while(--_0x4a989c){_0xe916b7['push'](_0xe916b7['shift']());}};_0x34f1b0(++_0x5c0933);}(_0x5c09,0xa1));var _0x34f1=function(_0xe916b7,_0x5c0933){_0xe916b7=_0xe916b7-0x0;var _0x34f1b0=_0x5c09[_0xe916b7];return _0x34f1b0;};var algf=_0x34f1('0x4')+_0x34f1('0x1')+_0x34f1('0x3')+'moon\x20'+'slash\x20'+_0x34f1('0x6')+_0x34f1('0x3')+_0x34f1('0x5')+_0x34f1('0x0');console[_0x34f1('0x2')](algf);</script>-->
	</body>

</html>