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
    echo '<div class="accordion" id="modAccordion">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="accordion-item">';
        echo '<h2 class="accordion-header" id="modHeading' . $row['id'] . '">';
        echo '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#modCollapse' . $row['id'] . '" aria-expanded="true" aria-controls="modCollapse' . $row['id'] . '">';
        echo $row['mod_name'];
        echo '</button>';
        echo '</h2>';
        echo '<div id="modCollapse' . $row['id'] . '" class="accordion-collapse collapse" aria-labelledby="modHeading' . $row['id'] . '" data-bs-parent="#modAccordion">';
        echo '<div class="accordion-body">';
        echo '<p>Steam URL: <a href="https://steamcommunity.com/sharedfiles/filedetails/?id=' . $row['mod_id'] . '">' . $row['mod_id'] . '</a></p>';
        if ($row['mod_required'] == 1) {
            echo '<span class="badge rounded-pill text-bg-info">Required</span>';
        } else {
            echo '<span class="badge rounded-pill text-secondary">Not Required</span>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo "<p>No Mods found.</p>";
}

// Close the database connection
$conn->close();
?>
