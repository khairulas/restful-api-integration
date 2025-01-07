<?php
$apiUrl = "http://localhost/ict651-project/api/resources";
$id = $_GET['id'] ?? '';
$response = json_decode(file_get_contents("$apiUrl/$id"), true);
$resource = $response['data'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Resource</title>
</head>
<body>
    <h1>View Resource</h1>
    <p>ID: <?= htmlspecialchars($resource['id'] ?? 'N/A') ?></p>
    <p>Name: <?= htmlspecialchars($resource['name'] ?? 'N/A') ?></p>
    <p>Description: <?= htmlspecialchars($resource['description'] ?? 'N/A') ?></p>
    <a href="index.php">Back</a>
</body>
</html>
