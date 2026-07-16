<?php

?>

<html>
<head>

<title>User records</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body class="p-3">

<h1 class="mb-4 mt-3 text-center">User Records</h1>

<table id="user-table"  class="table">
	<thead>
		<tr>
			<th scope="col">#</td>
			<th scope="col">Name</td>
			<th scope="col">Address</td>
			<th scope="col">Phone</td>
			<th scope="col">Email</td>
			<th scope="col">Birthday</td>
			<th scope="col">Company</td>
		</tr>
	</thead>
</table>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
	function load_user_info() {
		$.ajax({
			type: "GET",
			url: "api-v3/get-user-records.php",
			success: function(data) {
				data = JSON.parse(data);
				generate_table(data);
			}
		});
	}
	
	function generate_table(data) {
		var table = document.getElementById('user-table');
		data.forEach(function(object, index) {
			var tr = document.createElement('tr');
			tr.innerHTML = '<th scope="row">' + (index + 1) + '</th>' +
							'<td>' + object.name + '</td>' +
							'<td>' + object.address + '</td>' +
							'<td>' + object.phone + '</td>' +
							'<td>' + object.email + '</td>' +
							'<td>' + object.birthday + '</td>' +
							'<td>' + object.company + '</td>';
			table.appendChild(tr);
		});
	}
	
	load_user_info();
});
</script>

</body>
</html>
