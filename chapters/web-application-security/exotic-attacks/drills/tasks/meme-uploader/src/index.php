<?php

$error = "";
$success = "";

if (isset($_POST["submit"])) {
	$uploadOk = 1;
	$target_dir = "uploads/";
	$extension = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
	$file_name = md5($_FILES["fileToUpload"]["name"]) . '.' . $extension;
	$target_file = $target_dir . $file_name;

	// Check if file already exists
	if (file_exists($target_file)) {
	  $error = "Sorry, a file with this name already exists.";
	  $uploadOk = 0;
	}

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		$error = "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk != 0) {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			$success = "Your file ". $file_name . " has been uploaded successfully!";
		} else {
			$error = "Sorry, there was an error uploading your file.";
		}
	}
}
?>

<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	</head>
	<body>
		<div class="card mt-5" style="width: 48rem; margin: auto;">
			<div class="card-body">
				<h1>Meme uploader</h1>
				<?php if ($error != ''): ?>
					<div class="alert alert-danger" role="alert">
						<?php echo $error; ?>
					</div>
				<?php endif; ?>
				<form method="POST" enctype="multipart/form-data">
					Select meme to upload:
					<div class="input-group mb-3 mt-3">
					  <div class="custom-file">
						<input name="fileToUpload" type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
						<label class="custom-file-label" for="inputGroupFile01">Choose a meme from your computer...</label>
					  </div>
					</div>
					<input type="submit" value="Upload meme" name="submit" class="btn btn-primary">
				</form>
				<div>
					<?php if ($success != ''): ?>
						<div class="alert alert-success" role="alert">
							<?php echo $success; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</body>
</html>
