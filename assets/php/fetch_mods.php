<?php
// Database connection
$conn = new mysqli("localhost:3306", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch mod data
$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Generate HTML dynamically
if ($result->num_rows > 0) {
    echo '<div class="dropdown">';
    echo '<button class="btn btn-secondary dropdown-toggle" type="button" id="modDropdown" data-bs-toggle="dropdown" aria-expanded="false">Select a Mod</button>';
    echo '<ul class="dropdown-menu" aria-labelledby="modDropdown">';
    while ($row = $result->fetch_assoc()) {
        echo '<li><a class="dropdown-item" href="https://steamcommunity.com/sharedfiles/filedetails/?id=' . $row['mod_id'] . '">' . $row['mod_name'] . '</a></li>';
    }
    echo '</ul>';
    echo '</div>';
} else {
    echo "<p>No Mods found.</p>";
}

// Close the database connection
$conn->close();
?>
