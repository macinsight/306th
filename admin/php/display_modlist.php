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

// Function to delete a specific mod record
function deleteModRecord($modID)
{
    global $conn;

    $sql = "DELETE FROM modlist WHERE mod_id = '$modID'";
    $result = $conn->query($sql);

    return $result;
}

// Query to fetch mod data
$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

// Pagination settings
$entriesPerPage = 25;
$totalEntries = $result->num_rows;
$totalPages = ceil($totalEntries / $entriesPerPage);
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for database query
$offset = ($currentPage - 1) * $entriesPerPage;

// Query to fetch mod data for the current page
$sqlPage = "SELECT * FROM modlist ORDER BY id ASC LIMIT $offset, $entriesPerPage";
$resultPage = $conn->query($sqlPage);

// Cache duration in seconds (1 day)
$cacheDuration = 86400;

// Generate HTML dynamically
if ($resultPage->num_rows > 0) {
    echo '<form method="POST">'; // Start the form

    echo '<table class="table table-hover">';
    echo '<thead><tr><th>Mod Name</th><th>File Size (MB)</th><th>Required?</th><th>Delete?</th></tr></thead>';
    echo '<tbody>';
    while ($row = $resultPage->fetch_assoc()) {
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

            // Output the link with the mod title
            echo "<td><input type='checkbox' name='mod_delete[]' value='$modID'> <a href='https://steamcommunity.com/sharedfiles/filedetails/?id=$modID' class='link-offset-2 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover' target='_blank'>$modTitle</a></td>";
            echo "<td>$fileSizeMB MB</td>";
            echo "<td><input type='checkbox' name='mod_required[]' value='$modID'></td>";
        } else {
            // Output "N/A" if mod details are not available
            echo "<td>N/A</td>";
            echo "<td>N/A</td>";
            echo "<td>N/A</td>";
        }
        echo "</tr>";
    }
    echo '</tbody>';
    echo '</table>';

    echo '<div class="d-flex justify-content-between">'; // Start the container for Submit and Pagination

    echo '<button type="submit" class="btn btn-primary">Submit</button>'; // Add the submit button

    echo '<nav aria-label="Page navigation example">'; // Start the pagination element
    echo '<ul class="pagination">';

    // Previous page button
    echo '<li class="page-item ';
    if ($currentPage == 1) {
        echo 'disabled';
    }
    echo '">';
    echo '<a class="page-link" href="?page=' . ($currentPage - 1) . '" aria-label="Previous">';
    echo '<span aria-hidden="true">&laquo;</span>';
    echo '</a>';
    echo '</li>';

    // Page numbers
    for ($page = 1; $page <= $totalPages; $page++) {
        echo '<li class="page-item';
        if ($page == $currentPage) {
            echo ' active';
        }
        echo '"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
    }

    // Next page button
    echo '<li class="page-item ';
    if ($currentPage == $totalPages) {
        echo 'disabled';
    }
    echo '">';
    echo '<a class="page-link" href="?page=' . ($currentPage + 1) . '" aria-label="Next">';
    echo '<span aria-hidden="true">&raquo;</span>';
    echo '</a>';
    echo '</li>';

    echo '</ul>';
    echo '</nav>'; // End the pagination element

    echo '</div>'; // End the container for Submit and Pagination

    echo '</form>'; // End the form

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requiredMods = isset($_POST['mod_required']) ? $_POST['mod_required'] : [];

        foreach ($requiredMods as $modID) {
            $required = in_array($modID, $requiredMods) ? 1 : 0;
            updateModRequiredStatus($modID, $required);
        }

        $deleteMods = isset($_POST['mod_delete']) ? $_POST['mod_delete'] : [];

        foreach ($deleteMods as $modID) {
            deleteModRecord($modID);
        }

        // Refresh the page after updating the database
        header('Location: ' . $_SERVER['PHP_SELF'] . '?page=' . $currentPage);
        exit();
    }

    // Close the database connection
    $conn->close();
} else {
    echo "<p>No Mods found.</p>";
}
?>
