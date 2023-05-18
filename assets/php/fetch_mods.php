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
        $modID = $row['mod_id'];
        $apiUrl = "https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/";
        $postData = http_build_query([
            'itemcount' => 1,
            'format' => 'json',
            'publishedfileids[0]' => $modID
        ]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        $response = curl_exec($curl);
        curl_close($curl);

        // Check if the API request was successful
        if ($response) {
            $data = json_decode($response, true);

            // Check if the response contains mod details
            if ($data['response']['result'] == 1 && isset($data['response']['publishedfiledetails'][0])) {
                $fileSize = $data['response']['publishedfiledetails'][0]['file_size'];

                // Output the file size
                echo "<td>" . $fileSize . "</td>";
            } else {
                // Output "N/A" if file size is not available
                echo "<td>N/A</td>";
            }
        } else {
            // Output "N/A" if API request failed
            echo "<td>N/A</td>";
        }
        
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
