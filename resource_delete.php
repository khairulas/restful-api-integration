<?php
$id = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Resource</title>
</head>
<body>
    <h1>Delete Resource</h1>
    <p>Are you sure you want to delete this resource?</p>
    <form method="POST" action="resource_process.php?action=delete&id=<?= htmlspecialchars($id) ?>">
        <button type="submit">Yes, Delete</button>
    </form>
    <a href="index.php">Cancel</a>
</body>
</html>
