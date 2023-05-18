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
    echo '<table class="table table-hover">';
    echo '<thead><tr><th>Mod Name</th><th>Required?</th></tr></thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><a class='btn btn-link' href='https://steamcommunity.com/sharedfiles/filedetails/?id=" . $row['mod_id'] . "' target='_blank'>" . $row['mod_name'] . "</a></td>";
        echo "<td>";
        if ($row['mod_required'] == 1) {
            echo '<span class="badge rounded-pill text-bg-info">Required</span>';
        } else {
            echo '<span class="badge rounded-pill text-secondary">Not Required</span>';
        }
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
