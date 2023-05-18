<?php
// Database connection
$conn = new mysqli("localhost:3306", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query to fetch upcoming operations
$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Generate HTML dynamically
if ($result->num_rows > 0) {
  echo '<table class="table table-striped">';
  echo '<thead><tr><th>Mod Name</th><th>Steam URL</th></tr></thead>';
  echo '<tbody>';
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['mod_name'] . "</td>";
    echo "<td>" . $row['mod_id'] . "</td>";
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
