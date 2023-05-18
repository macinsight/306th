<?php
// Database connection
$conn = new mysqli("localhost:3306", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch all operations from the database
function fetchAllOperations() {
    global $conn;
    $sql = "SELECT * FROM operations";
    $result = $conn->query($sql);
    $operations = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $operations[] = $row;
        }
    }

    return $operations;
}

// Check if the form is submitted for creating or updating a record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a new record
    if (isset($_POST['create'])) {
        // Perform insert operation here
        // ...
    }

    // Update an existing record
    if (isset($_POST['update'])) {
        // Perform update operation here
        // ...
    }

    // Delete a record
    if (isset($_POST['delete'])) {
        // Perform delete operation here
        // ...
    }
}

// Fetch all operations
$operations = fetchAllOperations();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
	<script src="/assets/js/color-modes.js"></script>
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
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!-- Form fields for creating a record -->
        </form>

        <h2>Update Record</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!-- Form fields for updating a record -->
        </form>

        <h2>Delete Record</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!-- Form fields for deleting a record -->
        </form>

        <h2>All Operations</h2>
        <button type="button" class="btn btn-primary" id="fetchOperationsBtn">Fetch All Operations</button>
        <table class="table mt-3" id="operationsTable">
            <thead>
                <tr>
                    <th>Operation ID</th>
                    <th>Operation Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operations as $operation) { ?>
                <tr>
                    <td><?php echo $operation['id']; ?></td>
                    <td><?php echo $operation['operation_name']; ?></td>
                    <td><?php echo $operation['date']; ?></td>
                    <td><?php echo $operation['time']; ?></td>
                    <td><?php echo $operation['location']; ?></td>
                    <td><?php echo $operation['description']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fetch operations and update table on button click
        document.getElementById('fetchOperationsBtn').addEventListener('click', function() {
            fetchOperations();
        });

        function fetchOperations() {
            // Send an AJAX request to fetch all operations
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Parse the response and update the table
                        var operations = JSON.parse(xhr.responseText);
                        updateTable(operations);
                    } else {
                        console.error('Error fetching operations: ' + xhr.status);
                    }
                }
            };
            xhr.open('GET', 'fetch_operations.php', true);
            xhr.send();
        }

        function updateTable(operations) {
            var tableBody = document.getElementById('operationsTable').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = '';

            operations.forEach(function(operation) {
                var row = tableBody.insertRow();

                var idCell = row.insertCell();
                idCell.textContent = operation.id;

                var nameCell = row.insertCell();
                nameCell.textContent = operation.operation_name;

                var dateCell = row.insertCell();
                dateCell.textContent = operation.date;

                var timeCell = row.insertCell();
                timeCell.textContent = operation.time;

                var locationCell = row.insertCell();
                locationCell.textContent = operation.location;

                var descriptionCell = row.insertCell();
                descriptionCell.textContent = operation.description;
            });
        }
    </script>
</body>
</html>
