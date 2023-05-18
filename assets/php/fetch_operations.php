<?php
// Database connection
$conn = new mysqli("localhost:3306", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Set the default timezone to CST (Central Standard Time)
date_default_timezone_set('America/Chicago');

// Query to fetch upcoming operations
$sql = "SELECT * FROM operations ORDER BY date ASC";
$result = $conn->query($sql);

// Generate HTML dynamically
if ($result->num_rows > 0) {
  echo '<table class="table table-striped">';
  echo '<thead><tr><th>Operation Name</th><th><i class="bi bi-calendar2-event"></i> Date</th><th><i class="bi bi-stopwatch"></i> Time (Local)</th><th><i class="bi bi-geo-alt"></i> Location</th><th><i class="bi bi-card-text"></i> Description</th></tr></thead>';
  echo '<tbody>';
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['operation_name'] . "</td>";
    echo "<td>" . $row['date'] . "</td>";

    // Convert time to CST (Central Standard Time)
    $time = new DateTime($row['time'], new DateTimeZone('UTC'));
    $time->setTimezone(new DateTimeZone('America/Chicago'));
    echo "<td>" . $time->format('H:i') . "</td>";

    echo "<td>" . $row['location'] . "</td>";
    echo "<td>" . $row['description'] . "</td>";
    echo "</tr>";
  }
  echo '</tbody>';
  echo '</table>';
} else {
  echo "<p>No upcoming operations found.</p>";
}

// Close the database connection
$conn->close();
?>