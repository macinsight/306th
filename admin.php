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

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create or Update operation
    $modID = $_POST['mod_id'];
    $modRequired = isset($_POST['mod_required']) ? 1 : 0;

    if ($_POST['operation'] === 'create') {
        $sql = "INSERT INTO modlist (mod_id, mod_required) VALUES ('$modID', '$modRequired')";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success" role="alert">Mod added successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
        }
    } elseif ($_POST['operation'] === 'update') {
        $sql = "UPDATE modlist SET mod_required='$modRequired' WHERE mod_id='$modID'";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success" role="alert">Mod updated successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Delete operation
    if (isset($_GET['delete'])) {
        $modID = $_GET['delete'];
        $sql = "DELETE FROM modlist WHERE mod_id='$modID'";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="alert alert-success" role="alert">Mod deleted successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
        }
    }
}

// Fetch mod data
$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Include the HTML template
include('form-template.php');
?>
