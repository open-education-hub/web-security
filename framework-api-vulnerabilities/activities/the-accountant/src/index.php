<html>
<head>

<title>Accounting records</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body class="p-3">

<h1 class="mb-4 mt-3 text-center">Accounting Records</h1>

<div class="mb-4" style="max-width: 400px; margin:auto;">
	<p>Welcome back! Please see below a list of retailers whose records you have access to.</p>
	<select id="select-retailer" class="form-control">
		<option disabled selected>Select a retailer</option>
		<option value="emag">Emag</option>
		<option value="mediagalaxy">Media Galaxy</option>
		<option value="pcgarage">PC Garage</option>
		<!--<option value="flanco">Flanco</option>-->
		<!--<option value=""></option>-->
	</select>
</div>

<table id="user-table"  class="table">
	<thead>
		<tr>
			<th scope="col">#</td>
			<th scope="col">Transaction</td>
			<th scope="col">Date</td>
			<th scope="col">Amount</td>
			<th scope="col">Overdue</td>
			<th scope="col">Billed</td>
		</tr>
	</thead>
</table>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {

	$("#select-retailer").change(function() {
		var retailer = $(this).children("option:selected").val();
		$.ajax({
			type: "GET",
			url: "api-v2/retailers/records.php?retailer=" + retailer,
			success: function(data) {
				data = JSON.parse(data);
				generate_table(data);
			}
		});
	});

	function generate_table(data) {
		var table = document.getElementById('user-table');
		$('#user-table .entry').remove();
		data.forEach(function(object, index) {
			var tr = document.createElement('tr');
			tr.className = 'entry';
			tr.innerHTML = '<th scope="row">' + (index + 1) + '</th>' +
							'<td>' + object.transaction + '</td>' +
							'<td>' + object.date + '</td>' +
							'<td>' + object.amount + '</td>' +
							'<td>' + object.overdue + '</td>' +
							'<td>' + object.billed + '</td>';
			table.appendChild(tr);
		});
	}

});
</script>

</body>
</html>
