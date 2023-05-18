<!DOCTYPE html>
<html>

<head>
	<script src="../assets/js/color-modes.js"></script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="github.com/macinsight">
	<title>The 306th | Your next ArmA 3 unit</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<link href="css/cover.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<meta property="og:title" content="The 306th Assault Brigade" />
	<meta name="description"
		content="The 306th is recruiting! Join the Discord for more info - 1980-1997 Bundeswehr Mechanized Arma 3 Unit.">
	<meta property="og:url" content="https://306th.macinsight.net" />
	<meta property="og:image" content="https://306th.macinsight.net/assets/img/306th.png" />
	<meta name="keywords"
		content="ArmA, ArmA 3, unit, milsim, Bundeswehr, German, semi-milsim, tactical combat, immersive, missions, camaraderie, milsim experience">
	<meta name="theme-color" content="#4a5342">
</head>

<body>
	<div class="container">
		<h1>CRUD Operations</h1>

		<h2>Create Record</h2>
		<form method="POST" action="../assets/php/manage_operations_db.php">
			<div class="mb-3">
				<label for="operation_name" class="form-label">Operation Name:</label>
				<input type="text" class="form-control" name="operation_name" required>
			</div>

			<div class="mb-3">
				<label for="date" class="form-label">Date:</label>
				<input type="date" class="form-control" name="date" required>
			</div>

			<div class="mb-3">
				<label for="time" class="form-label">Time:</label>
				<input type="time" class="form-control" name="time" required>
			</div>

			<div class="mb-3">
				<label for="location" class="form-label">Location:</label>
				<input type="text" class="form-control" name="location" required>
			</div>

			<div class="mb-3">
				<label for="description" class="form-label">Description:</label>
				<textarea class="form-control" name="description" required></textarea>
			</div>

			<button type="submit" class="btn btn-primary" name="create">Create</button>
		</form>

		<h2>Update Record</h2>
		<form method="POST" action="../assets/php/manage_operations_db.php">
			<div class="mb-3">
				<label for="operation_id" class="form-label">Operation ID:</label>
				<input type="text" class="form-control" name="operation_id" required>
			</div>

			<div class="mb-3">
				<label for="operation_name" class="form-label">Operation Name:</label>
				<input type="text" class="form-control" name="operation_name" required>
			</div>

			<div class="mb-3">
				<label for="date" class="form-label">Date:</label>
				<input type="date" class="form-control" name="date" required>
			</div>

			<div class="mb-3">
				<label for="time" class="form-label">Time:</label>
				<input type="time" class="form-control" name="time" required>
			</div>

			<div class="mb-3">
				<label for="location" class="form-label">Location:</label>
				<input type="text" class="form-control" name="location" required>
			</div>

			<div class="mb-3">
				<label for="description" class="form-label">Description:</label>
				<textarea class="form-control" name="description" required></textarea>
			</div>

			<button type="submit" class="btn btn-primary" name="update">Update</button>
		</form>

		<h2>Delete Record</h2>
		<form method="POST" action="../assets/php/manage_operations_db.php">
			<div class="mb-3">
				<label for="operation_id" class="form-label">Operation ID:</label>
				<input type="text" class="form-control" name="operation_id" required>
			</div>

			<button type="submit" class="btn btn-danger" name="delete">Delete</button>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>