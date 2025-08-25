<?php

Class GPLSourceBloater {
    public function __toString()
    {
        return highlight_file('license.txt', true) . highlight_file($this->source, true);
    }
}

if (isset($_GET['source'])){
    $s = new GPLSourceBloater();
    $s->source = __FILE__;

    echo $s;
    exit;
}

$todos = [];

if (isset($_COOKIE['todos'])) {
    $c = $_COOKIE['todos'];
    $h = substr($c, 0, 32);
    $m = substr($c, 32);

    if(md5($m) === $h) {
        $todos = unserialize($m);
    }
}

if (isset($_POST['text']) && strlen($_POST['text']) > 1) {
    $todo = $_POST['text'];

    $todos[] = $todo;
    $m = serialize($todos);
    $h = md5($m);

    setcookie('todos', $h.$m);

    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

?>
<html>
	<head>
		<title>TODO App</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	</head>
	<body>
		<div style="width: 30rem; margin: 40px auto;">
			<h1 class="mt-4 mb-4">TODO App</h1>
			<p>TODOs:</p>
			<ul>
				<?php foreach($todos as $todo): ?>
					<li><?php echo $todo; ?></li>
				<?php endforeach;?>
			</ul>

			<form method="POST">
				<textarea name="text" class="form-control"></textarea>
				<input type="submit" value="Store" class="btn btn-primary mt-3">
			</form>
			<div style="position: fixed; bottom: 4px; right: 15px;">
				<p><a href="?source">Open source license</a></p>
			</div>
		</div>
	</body>
</html>
