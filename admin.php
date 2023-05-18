<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Create Operation</h2>
        <form method="POST" action="your_php_script.php">
            <div class="form-group">
                <label for="operation_name">Operation Name:</label>
                <input type="text" class="form-control" id="operation_name" name="operation_name" required>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="time">Time:</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" name="create">Create</button>
        </form>

        <h2>Update Operation</h2>
        <form method="POST" action="your_php_script.php">
            <div class="form-group">
                <label for="operation_id">Operation ID:</label>
                <input type="text" class="form-control" id="operation_id" name="operation_id" required>
            </div>

            <div class="form-group">
                <label for="operation_name">Operation Name:</label>
                <input type="text" class="form-control" id="operation_name" name="operation_name" required>
            </div>

            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <div class="form-group">
                <label for="time">Time:</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>

            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" name="update">Update</button>
        </form>

        <h2>Delete Operation</h2>
        <form method="POST" action="your_php_script.php">
            <div class="form-group">
                <label for="operation_id">Operation ID:</label>
                <input type="text" class="form-control" id="operation_id" name="operation_id" required>
            </div>

            <button type="submit" class="btn btn-danger" name="delete">Delete</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
