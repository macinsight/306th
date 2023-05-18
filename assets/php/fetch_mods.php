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
    echo '<thead><tr><th>Mod Name</th><th>File Size</th><th>Required?</th></tr></thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><a class='link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover' href='https://steamcommunity.com/sharedfiles/filedetails/?id=" . $row['mod_id'] . "'>" . $row['mod_name'] . "</a></td>";
        
        // Query Steam API for file size
        $steamAPIKey = "198E9A22758D200BC3BF16C1A4FE9130";
        $modID = $row['mod_id'];
        $url = "https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/?key=$steamAPIKey&itemcount=1&publishedfileids[0]=$modID";
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        $fileSize = isset($data['response']['publishedfiledetails'][0]['file_size']) ? $data['response']['publishedfiledetails'][0]['file_size'] : 'N/A';
        
        echo "<td>" . $fileSize . "</td>";
        
        echo "<td>";
        if ($row['mod_required'] == 1) {
            echo '<span class="badge rounded-pill text-success text-bg-info">Required</span>';
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
