<?php
require_once 'display.php';
require_once 'config.php';
require_once 'functions.php';

displayThemeSelector();
displayHead();
displayNavbar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_item'])) {
        $newItem = $_POST['new_item'];

        // Extract Workshop ID from the URL, if provided
        $workshopID = extractWorkshopID($newItem);

        // Query Steam API for mod details
        $apiUrl = "https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/";
        $postData = http_build_query([
            'itemcount' => 1,
            'format' => 'json',
            'publishedfileids[0]' => $workshopID
        ]);

        // Fetch API response and store it in cache
        $cacheFile = "cache/$workshopID.cache";
        $response = fetchAPIResponse($apiUrl, $postData, $cacheFile);

        // Process API response
        $data = json_decode($response, true);

        // Check if the response contains mod details
        if ($data['response']['result'] == 1 && isset($data['response']['publishedfiledetails'][0])) {
            $fileDetails = $data['response']['publishedfiledetails'][0];
            $modTitle = $fileDetails['title'];
            $fileSizeBytes = isset($fileDetails['file_size']) ? $fileDetails['file_size'] : 0;
            $fileSizeMB = round($fileSizeBytes / (1024 * 1024), 2);

            // Insert the new item into the database
            $sql = "INSERT INTO modlist (mod_id, mod_required) VALUES (?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $workshopID);
            $stmt->execute();
            $stmt->close();

            // Delete the cache file for the new item
            deleteCacheFile($workshopID);
        }
    }

    // Handle required status updates
    if (isset($_POST['mod_required'])) {
        $modRequired = $_POST['mod_required'];
        foreach ($modRequired as $modID) {
            updateModRequiredStatus($modID, 1);
        }
    }

    // Handle mod deletions
    if (isset($_POST['delete_mod'])) {
        $modsToDelete = $_POST['delete_mod'];
        foreach ($modsToDelete as $modID) {
            deleteMod($modID);
        }
    }

    // Redirect to avoid form resubmission on page refresh
    header("Location: modlist.php");
    exit();
}

$sql = "SELECT * FROM modlist ORDER BY id ASC";
$result = $conn->query($sql);

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
        echo "<label class='form-check-label' for='switch_$modID'></label>";
        echo "</div>";
        echo "</td>";
        echo "<td>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='checkbox' name='delete_mod[]' value='$modID'>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }

    echo '</tbody>';
    echo '</table>';

    // Display the "Add New Mod" input field and submit button
    echo '<div class="form-group mt-3">';
    echo '<input type="text" class="form-control" name="new_item" placeholder="Add new mod (Steam Workshop ID or URL)">';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Submit</button>';

    echo '</form>'; // End the form
}

$conn->close();
displayFooter();
?>
