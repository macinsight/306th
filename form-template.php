<!-- Display the CRUD form -->
<div class="container mt-5">
    <h3>Mod List</h3>
    <form method="post" action="">
        <div class="mb-3">
            <label for="modID" class="form-label">Mod ID</label>
            <input type="text" class="form-control" id="modID" name="mod_id" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="modRequired" name="mod_required">
            <label class="form-check-label" for="modRequired">Required</label>
        </div>
        <input type="hidden" name="operation" id="operation" value="create">
        <button type="submit" class="btn btn-primary">Create</button>
    </form>

    <?php if ($result->num_rows > 0) : ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Mod Name</th>
                    <th>File Size</th>
                    <th>Required?</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <?php
                        $modID = $row['mod_id'];
                        $modRequired = $row['mod_required'];

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
                            $fileSize = isset($fileDetails['file_size']) ? round($fileDetails['file_size'] / (1024 * 1024), 2) . ' MB' : 'N/A';
                        } else {
                            // Set default values if mod details are not available
                            $modTitle = 'N/A';
                            $fileSize = 'N/A';
                        }
                        ?>
                        <td><?php echo $modTitle; ?></td>
                        <td><?php echo $fileSize; ?></td>
                        <td>
                            <?php if ($modRequired == 1) : ?>
                                <span class="badge rounded-pill text-success text-bg-info">Required</span>
                            <?php else : ?>
                                <span class="badge rounded-pill text-secondary">Not Required</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?delete=<?php echo $modID; ?>" class="btn btn-danger btn-sm">Delete</a>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo $modID; ?>">
                                Edit
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal-<?php echo $modID; ?>" tabindex="-1" aria-labelledby="editModalLabel-<?php echo $modID; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-<?php echo $modID; ?>">Edit Mod</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="">
                                        <div class="mb-3">
                                            <label for="editModID-<?php echo $modID; ?>" class="form-label">Mod ID</label>
                                            <input type="text" class="form-control" id="editModID-<?php echo $modID; ?>" name="mod_id" value="<?php echo $modID; ?>" required readonly>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="editModRequired-<?php echo $modID; ?>" name="mod_required" <?php if ($modRequired == 1) echo 'checked'; ?>>
                                            <label class="form-check-label" for="editModRequired-<?php echo $modID; ?>">Required</label>
                                        </div>
                                        <input type="hidden" name="operation" value="update">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No mods found.</p>
    <?php endif; ?>

</div>
