<?php

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

// Function to extract Workshop ID from the URL
function extractWorkshopID($url) {
    $pattern = '/(?:https?:\/\/)?steamcommunity\.com\/sharedfiles\/filedetails\/\?id=(\d+)/i';
    preg_match($pattern, $url, $matches);
    return isset($matches[1]) ? $matches[1] : $url;
}

// Function to delete a mod from the database and delete its cache file
function deleteMod($modID) {
    global $conn;
    deleteCacheFile($modID);

    $sql = "DELETE FROM modlist WHERE mod_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $modID);
    $stmt->execute();
    $stmt->close();
}