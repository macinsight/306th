<?php
// Database connection
$conn = new mysqli("localhost:3306", "306_ops", "buG9*9x23!!", "operations");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query to fetch mods
$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Generate HTML dynamically
if ($result->num_rows > 0) {
  echo '<table class="table table-hover">';
  echo '<thead><tr><th>Mod Name</th><th>Steam URL</th><th>Required?</th><th>New addition</th><th>Rating</th></tr></thead>';
  echo '<tbody>';
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['mod_name'] . "</td>";
    echo "<td><a href='https://steamcommunity.com/sharedfiles/filedetails/?id=" . $row['mod_id'] . "'>" . $row['mod_id'] . "</a></td>";
    echo "<td>";
    if ($row['mod_required'] == 1) {
      echo '<span class="badge rounded-pill text-primary">Required</span>';
    } else {
      echo '<span class="badge rounded-pill text-secondary">Not Required</span>';
    }
    echo "</td>";

    // Check if mod is a new addition
    $creationDate = strtotime($row['creation_date']);
    $currentDate = time();
    $oneWeekAgo = strtotime('-1 week', $currentDate);
    echo "<td>";
    if ($creationDate >= $oneWeekAgo) {
      echo '<span class="badge rounded-pill text-success">New Addition</span>';
    }
    echo "</td>";

    echo "<td>";
    $steamWorkshopId = $row['mod_id'];
    $steamApiUrl = "https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/?itemcount=1&publishedfileids[0]=$steamWorkshopId&format=json";
    $steamApiResponse = file_get_contents($steamApiUrl);
    $steamApiResponse = json_decode($steamApiResponse, true);

    if (isset($steamApiResponse['response']['publishedfiledetails'][0]['averagerating'])) {
      $rating = $steamApiResponse['response']['publishedfiledetails'][0]['averagerating'];
      echo "Rating: " . $rating;
    } else {
      echo "Rating: N/A";
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
