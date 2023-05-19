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

// Function to update the required status for a specific mod
function updateModRequiredStatus($modID, $required)
{
    global $conn;

    $sql = "UPDATE modlist SET mod_required = '$required' WHERE mod_id = '$modID'";
    $result = $conn->query($sql);

    return $result;
}



$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Cache duration in seconds (1 day)
$cacheDuration = 86400;

// Generate HTML dynamically
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
            echo "<td>";
            echo "<div class='form-check'>";
            echo "<input class='form-check-input' type='checkbox' id='checkbox_$modID' name='delete_mod[]' value='$modID'>";
            echo "<label class='form-check-label' for='checkbox_$modID'></label>";
            echo "</div>";
            echo "</td>";
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
        echo "</tr>";
    }
    echo '</tbody>';
    echo '</table>';

    echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">Submit</button>'; // Add the submit button

    echo '</form>'; // End the form

    // Confirmation Modal
    echo '
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the selected mods?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    ';

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requiredMods = isset($_POST['mod_required']) ? $_POST['mod_required'] : [];

        foreach ($requiredMods as $modID) {
            $required = in_array($modID, $requiredMods) ? 1 : 0;
            updateModRequiredStatus($modID, $required);
        }

        $deleteMods = isset($_POST['delete_mod']) ? $_POST['delete_mod'] : [];

        // Prepare the record for deletion
        $deleteSql = "UPDATE modlist SET to_be_deleted = 1 WHERE mod_id IN ('" . implode("','", $deleteMods) . "')";
        $conn->query($deleteSql);

        // Refresh the page after updating the database
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Close the database connection
    $conn->close();
} else {
    echo "<p>No Mods found.</p>";
}