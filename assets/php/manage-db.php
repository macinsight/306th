<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Database connection
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'];

$conn = new mysqli($host . ':' . $port, $username, $password, $database);
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
