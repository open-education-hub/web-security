<?php

$error = "";
$result = "";

if (isset($_GET['submit'])) {
	if (isset($_GET['needle']) && isset($_GET['haystack']) && isset($_GET['replacement'])) {
		$result = preg_replace('/' . $_GET['needle'], $_GET['replacement'], $_GET['haystack']);
	}
}

?>

<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	</head>
	<body>
		<section>
			<div class="card mt-5" style="width: 48rem; margin: auto;">
				<div class="card-body">
					<h1>PRO Replacer</h1>
					<?php if ($error != ''): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $error; ?>
						</div>
					<?php endif; ?>
					<form method="GET">
						<div class="form-group">
							<label for="needle">Needle</label>
							<input type="text" name="needle" class="form-control" id="needle">
						</div>
						<div class="form-group">
							<label for="replacement">Replacement</label>
							<input type="text" name="replacement" class="form-control" id="replacement">
						</div>
						<div class="form-group">
							<label for="haystack">Haystack</label>
							<textarea name="haystack" class="form-control" id="haystack"></textarea>
						</div>
						<input type="submit" class="btn btn-primary" name="submit" value="Replace" />
					</form>

					<?php if ($result != ''): ?>
						<div class="card mt-3">
							<div class="card-body">
								<h3>Result</h3>
								<hr />
								<p><?php echo $result; ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	</body>
</html>
