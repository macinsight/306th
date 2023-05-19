<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Database connection
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'];

$conn = new mysqli($host . ':' . $port, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch API response and store it in cache
function fetchAPIResponse($apiUrl, $postData, $cacheFile)
{
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postData
        ]
    ]);

    $response = file_get_contents($apiUrl, false, $context);

    // Save the response to cache file
    file_put_contents($cacheFile, $response);

    return $response;
}

// Function to retrieve API response from cache if available and not expired
function retrieveAPIResponseFromCache($cacheFile, $cacheDuration)
{
    if (file_exists($cacheFile)) {
        $fileModifiedTime = filemtime($cacheFile);
        $currentTime = time();
        $elapsedTime = $currentTime - $fileModifiedTime;

        if ($elapsedTime <= $cacheDuration) {
            return file_get_contents($cacheFile);
        }
    }

    return false;
}

// Query to fetch mod data
$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Cache duration in seconds (1 day)
$cacheDuration = 86400;

// Variable to store the total file size
$totalFileSize = 0;

// Generate HTML dynamically
if ($result->num_rows > 0) {
    echo '<table class="table table-hover">';
    echo '<thead><tr><th>Mod Name</th><th>File Size (MB)</th><th>Required?</th></tr></thead>';
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        $modID = $row['mod_id'];

        // Define cache file path
        $cacheFile = "cache/$modID.cache";

        // Check if API response is available in cache and not expired
        $response = retrieveAPIResponseFromCache($cacheFile, $cacheDuration);

        if (!$response) {
            // Query Steam API for mod details
            $apiUrl = "https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/";
            $postData = http_build_query([
                'itemcount' => 1,
                'format' => 'json',
                'publishedfileids[0]' => $modID
            ]);

            // Fetch API response and store it in cache
            $response = fetchAPIResponse($apiUrl, $postData, $cacheFile);
        }

        // Process API response
        $data = json_decode($response, true);

        // Check if the response contains mod details
        if ($data['response']['result'] == 1 && isset($data['response']['publishedfiledetails'][0])) {
            $fileDetails = $data['response']['publishedfiledetails'][0];
            $modTitle = $fileDetails['title'];
            $fileSizeBytes = isset($fileDetails['file_size']) ? $fileDetails['file_size'] : 0;
            $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);

            // Increment the total file size
            $totalFileSize += $fileSizeMB;

            // Output the link with the mod title
            echo "<td><a href='https://steamcommunity.com/sharedfiles/filedetails/?id=$modID' class='link-offset-2 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover' target='_blank'>$modTitle</a></td>";
            echo "<td>$fileSizeMB MB</td>";
        } else {
            // Output "N/A" if mod details are not available
            echo "<td>N/A</td>";
            echo "<td>N/A</td>";
        }

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

    // Display the total file size
    echo "<p>Total File Size: $totalFileSize MB</p>";
} else {
    echo "<p>No Mods found.</p>";
}

// Close the database connection
$conn->close();
?>
