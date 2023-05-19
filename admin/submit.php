<?php

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
    $stmt->bind_param("s", implode(',', $deleteMods));
    $stmt->execute();
    $stmt->close();
}
