<?php
$steamApiKey = '198E9A22758D200BC3BF16C1A4FE9130';

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
  echo '<table class="table table-hover">';
  echo '<thead><tr><th>Mod Name</th><th>Steam URL</th><th>Required?</th><th>New Addition</th></tr></thead>';
  echo '<tbody>';
  while ($row = $result->fetch_assoc()) {
    $modId = $row['mod_id'];
    $steamUrl = "https://steamcommunity.com/sharedfiles/filedetails/?id=" . $modId;

    // Get mod details from Steam Workshop API
    $steamApiUrl = "https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/?itemcount=1&publishedfileids[0]=$modId&format=json&key=$steamApiKey";
    $modDetails = json_decode(file_get_contents($steamApiUrl), true);

    // Check if mod details are available
    if (isset($modDetails['response']['publishedfiledetails'][0])) {
      $modName = $modDetails['response']['publishedfiledetails'][0]['title'];
      $modRequired = $row['mod_required'];

      echo "<tr>";
      echo "<td>$modName</td>";
      echo "<td><a href='$steamUrl'>$modId</a></td>";
      echo "<td>$modRequired</td>";
      echo "<td>";
      echo "</td>";
      echo "</tr>";
    }
  }
  echo '</tbody>';
  echo '</table>';
} else {
  echo "<p>No Mods found.</p>";
}

// Close the database connection
$conn->close();
?>