<?php
require_once 'config.php';
require_once 'functions.php';

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
?>
