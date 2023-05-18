<?php
// Database connection
$conn = new mysqli("localhost", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query to fetch upcoming operations
$sql = "SELECT * FROM operations WHERE date >= CURDATE() ORDER BY date ASC";
$result = $conn->query($sql);

// Generate HTML dynamically
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<div class='operation'>";
    echo "<h3>" . $row['operation_name'] . "</h3>";
    echo "<p>Date: " . $row['date'] . "</p>";
    echo "<p>Time: " . $row['time'] . "</p>";
    echo "<p>Location: " . $row['location'] . "</p>";
    echo "<p>Description: " . $row['description'] . "</p>";
    echo "</div>";
  }
} else {
  echo "No upcoming operations found.";
}

// Close the database connection
$conn->close();
?>
