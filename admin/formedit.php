<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>CRUD Operations</h1>

        <h2>Create Record</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="createName" class="form-label">Operation Name</label>
                <input type="text" class="form-control" id="createName" name="createName" required>
            </div>
            <div class="mb-3">
                <label for="createDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="createDate" name="createDate" required>
            </div>
            <div class="mb-3">
                <label for="createTime" class="form-label">Time</label>
                <input type="time" class="form-control" id="createTime" name="createTime" required>
            </div>
            <div class="mb-3">
                <label for="createLocation" class="form-label">Location</label>
                <input type="text" class="form-control" id="createLocation" name="createLocation" required>
            </div>
            <div class="mb-3">
                <label for="createDescription" class="form-label">Description</label>
                <textarea class="form-control" id="createDescription" name="createDescription" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="create">Create</button>
        </form>

        <h2>Update Record</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="updateId" class="form-label">Operation ID</label>
                <input type="text" class="form-control" id="updateId" name="updateId" required>
            </div>
            <div class="mb-3">
                <label for="updateName" class="form-label">Operation Name</label>
                <input type="text" class="form-control" id="updateName" name="updateName" required>
            </div>
            <div class="mb-3">
                <label for="updateDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="updateDate" name="updateDate" required>
            </div>
            <div class="mb-3">
                <label for="updateTime" class="form-label">Time</label>
                <input type="time" class="form-control" id="updateTime" name="updateTime" required>
            </div>
            <div class="mb-3">
                <label for="updateLocation" class="form-label">Location</label>
                <input type="text" class="form-control" id="updateLocation" name="updateLocation" required>
            </div>
            <div class="mb-3">
                <label for="updateDescription" class="form-label">Description</label>
                <textarea class="form-control" id="updateDescription" name="updateDescription" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="update">Update</button>
        </form>

        <h2>Delete Record</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="deleteId" class="form-label">Operation ID</label>
                <input type="text" class="form-control" id="deleteId" name="deleteId" required>
            </div>
            <button type="submit" class="btn btn-danger" name="delete">Delete</button>
        </form>

        <h2>All Operations</h2>
        <button type="button" class="btn btn-primary" id="fetchOperationsBtn">Fetch All Operations</button>
        <table class="table mt-3" id="operationsTable">
            <thead>
                <tr>
                    <th>Operation ID</th>
                    <th>Operation Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($operations as $operation) { ?>
                <tr>
                    <td><?php echo $operation['id']; ?></td>
                    <td><?php echo $operation['operation_name']; ?></td>
                    <td><?php echo $operation['date']; ?></td>
                    <td><?php echo $operation['time']; ?></td>
                    <td><?php echo $operation['location']; ?></td>
                    <td><?php echo $operation['description']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fetch operations and update table on button click
        document.getElementById('fetchOperationsBtn').addEventListener('click', function() {
            fetchOperations();
        });

        function fetchOperations() {
            // Send an AJAX request to fetch all operations
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Parse the response and update the table
                        var operations = JSON.parse(xhr.responseText);
                        updateTable(operations);
                    } else {
                        console.error('Error fetching operations: ' + xhr.status);
                    }
                }
            };
            xhr.open('GET', 'fetch_operations.php', true);
            xhr.send();
        }

        function updateTable(operations) {
            var tableBody = document.getElementById('operationsTable').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = '';

            operations.forEach(function(operation) {
                var row = tableBody.insertRow();

                var idCell = row.insertCell();
                idCell.textContent = operation.id;

                var nameCell = row.insertCell();
                nameCell.textContent = operation.operation_name;

                var dateCell = row.insertCell();
                dateCell.textContent = operation.date;

                var timeCell = row.insertCell();
                timeCell.textContent = operation.time;

                var locationCell = row.insertCell();
                locationCell.textContent = operation.location;

                var descriptionCell = row.insertCell();
                descriptionCell.textContent = operation.description;
            });
        }
    </script>
</body>
</html>
