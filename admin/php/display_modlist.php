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

// Function to delete the cache file for a specific mod
function deleteCacheFile($modID)
{
    $cacheFile = "cache/$modID.cache";
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
}

// Function to update the required status for a specific mod
function updateModRequiredStatus($modID, $required)
{
    global $conn;

    $sql = "UPDATE modlist SET mod_required = ? WHERE mod_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $required, $modID);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

// Handle form submission and new item addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredMods = isset($_POST['mod_required']) ? $_POST['mod_required'] : [];

    // Reset all mod_required values to 0
    $resetSql = "UPDATE modlist SET mod_required = 0";
    $conn->query($resetSql);

    // Update mod_required status for selected mods
    foreach ($requiredMods as $modID) {
        $required = in_array($modID, $requiredMods) ? 1 : 0;
        updateModRequiredStatus($modID, $required);
    }

    $deleteMods = isset($_POST['delete_mod']) ? $_POST['delete_mod'] : [];

    // Delete the selected mods from the database
    $deleteSql = "DELETE FROM modlist WHERE mod_id IN (?)";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("s", implode(",", $deleteMods));
    $stmt->execute();
    $stmt->close();

    // Delete the cache files for the deleted mods
    foreach ($deleteMods as $modID) {
        deleteCacheFile($modID);
    }

    // Add new item if provided
    $newItem = isset($_POST['new_item']) ? trim($_POST['new_item']) : '';
    if (!empty($newItem)) {
        // Extract ID from link if provided
        if (strpos($newItem, 'steamcommunity.com/sharedfiles/filedetails/?id=') !== false) {
            $url = parse_url($newItem);
            parse_str($url['query'], $query);
            if (isset($query['id'])) {
                $newItem = $query['id'];
            }
        }

        // Add the new item
        $insertSql = "INSERT INTO modlist (mod_id, mod_required) VALUES (?, 0)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("s", $newItem);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect to refresh the page after submitting
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Cache duration in seconds (1 day)
$cacheDuration = 86400;

if ($result->num_rows > 0) {
    echo '<form method="POST" id="modForm">'; // Start the form

    echo '<table class="table table-hover">';
    echo '<thead><tr><th>Mod Name</th><th>File Size (MB)</th><th>Required?</th><th>Delete?</th></tr></thead>';
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

            // Output the checkbox, mod title, and other mod details
            echo "<td><a href='https://steamcommunity.com/sharedfiles/filedetails/?id=$modID' class='link-offset-2 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover' target='_blank'>$modTitle</a></td>";
            echo "<td>$fileSizeMB MB</td>";
        } else {
            // Output "N/A" if mod details are not available
            echo "<td></td>";
            echo "<td>N/A</td>";
            echo "<td>N/A</td>";
        }

        echo "<td>";
        echo "<div class='form-check form-switch'>";
        echo "<input class='form-check-input' type='checkbox' role='switch' id='switch_$modID' name='mod_required[]' value='$modID'" . ($row['mod_required'] == 1 ? ' checked' : '') . ">";
        echo "<label class='form-check-label' for='switch_$modID'>Required</label>";
        echo "</div>";
        echo "</td>";
        echo "<td>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='checkbox' id='checkbox_$modID' name='delete_mod[]' value='$modID'>";
        echo "<label class='form-check-label' for='checkbox_$modID'></label>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
    echo '</tbody>';
    echo '</table>';

    echo '<button type="submit" class="btn btn-primary">Submit</button>'; // Change to submit button

    // Add the input form for adding new Steam Workshop items
    echo '
    <div class="mb-3 mt-3" >
        <label for="newItemInput" class="form-label">Add New Item</label>
        <input type="text" class="form-control" id="newItemInput" name="new_item" placeholder="Enter ID or Link">
    </div>
    ';

    echo '</form>'; // End the form
}

$conn->close();
?>
