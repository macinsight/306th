<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mod List CRUD</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container py-4">
        <h1>Mod List</h1>

        <h2>Create Mod</h2>
        <form method="post" action="create.php">
            <div class="mb-3">
                <label for="modID" class="form-label">Mod ID</label>
                <input type="text" class="form-control" id="modID" name="mod_id" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="modRequired" name="mod_required">
                <label class="form-check-label" for="modRequired">Required</label>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>

        <h2>Mod List</h2>
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

        // Query to fetch mod data
        $sql = "SELECT * FROM modlist ORDER BY id ASC";
        $result = $conn->query($sql);

        // Check if there are any mods in the list
        if ($result->num_rows > 0) {
            echo '<table class="table table-hover">';
            echo '<thead><tr><th>Mod ID</th><th>Required?</th><th>Actions</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['mod_id'] . "</td>";
                echo "<td>";
                if ($row['mod_required'] == 1) {
                    echo '<span class="badge rounded-pill text-success text-bg-info">Required</span>';
                } else {
                    echo '<span class="badge rounded-pill text-secondary">Not Required</span>';
                }
                echo "</td>";
                echo "<td>";
                echo '<a href="update.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm me-2">Update</a>';
                echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                echo "</td>";
                echo "</tr>";
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo "<p>No Mods found.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>

</html>
