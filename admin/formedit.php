<?php
// Database connection
$conn = new mysqli("localhost:3306", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted for creating or updating a record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['create'])) {
        // Create a new record
        $operationName = $_POST['operation_name'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $description = $_POST['description'];

        $sql = "INSERT INTO operations (operation_name, date, time, location, description) VALUES ('$operationName', '$date', '$time', '$location', '$description')";
        if ($conn->query($sql) === true) {
            echo "Record created successfully.";
        } else {
            echo "Error creating record: " . $conn->error;
        }
    } elseif (isset($_POST['update'])) {
        // Update an existing record
        $operationId = $_POST['operation_id'];
        $operationName = $_POST['operation_name'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = $_POST['location'];
        $description = $_POST['description'];

        $sql = "UPDATE operations SET operation_name='$operationName', date='$date', time='$time', location='$location', description='$description' WHERE id=$operationId";
        if ($conn->query($sql) === true) {
            echo "Record updated successfully.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        // Delete a record
        $operationId = $_POST['operation_id'];

        $sql = "DELETE FROM operations WHERE id=$operationId";
        if ($conn->query($sql) === true) {
            echo "Record deleted successfully.";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>CRUD Operations</h1>

        <h2>Create Record</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
