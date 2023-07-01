<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['source'])) {
  die(highlight_file(__FILE__));
}

require("login.php");
error_reporting(0);

session_start();

if (isset($_POST['surname'])) {
  $query = "SELECT * FROM users WHERE name = '" . $_POST['surname'] . "'";
	$result = $conn->query($query);
}
?>

<!doctype html>
<html lang="en" style="height: 100%;">
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>Demo SQL injection</title>
</head>

<body style="height: 100%;">
  <div class="border d-flex align-items-center justify-content-center h-50">
    <form method="POST" action="/index.php">
      <div class="form-group">
        <label for="searchSurname">Search user by surname</label>
        <input name="surname" type="text" class="form-control" aria-describedby="surnameHelp" placeholder="Surname">
        <small id="emailHelp" class="form-text text-muted">Funny, do you think you can find me?</small>
      </div>

      <button type="submit" class="btn btn-primary">Find if you can</button>
    </form>

  </div>
  <div class="border d-flex align-items-center justify-content-center h-50">
  <?php
					if (isset($result) && $result->num_rows > 0) {
					    while($row = $result->fetch_assoc()) {
						echo "<small id=\"emailHelp\" class=\"form-text text-muted\">" . htmlspecialchars($row['name']) . "</small>";
					    }
					} else {
						echo "<small id=\"emailHelp\" class=\"form-text text-muted\">". "Not that simple bro" . "</small>";
					}
		?>
  </div>

</body>

</html>

