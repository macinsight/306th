<?php
// Database connection
$conn = new mysqli("localhost:3306", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $operationName = $_POST['operation_name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Update the database record
    $sql = "UPDATE operations SET date='$date', time='$time', location='$location', description='$description' WHERE operation_name='$operationName'";
    if ($conn->query($sql) === true) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Operation</title>
</head>
<body>
    <h1>Update Operation</h1>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="operation_name">Operation Name:</label>
        <input type="text" name="operation_name" required><br><br>

        <label for="date">Date:</label>
        <input type="date" name="date" required><br><br>

        <label for="time">Time:</label>
        <input type="time" name="time" required><br><br>

        <label for="location">Location:</label>
        <input type="text" name="location" required><br><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>