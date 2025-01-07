<?php
$apiUrl = "http://localhost/ict651-project-demo/api/resources";
$id = $_GET['id'] ?? '';
$response = json_decode(file_get_contents("$apiUrl/$id"), true);
$resource = $response['data'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Resource</title>
</head>
<body>
    <h1>Update Resource</h1>
    <form method="POST" action="resource_process.php?action=update&id=<?= htmlspecialchars($id) ?>">
        <input type="text" name="name" value="<?= htmlspecialchars($resource['name'] ?? '') ?>" required>
        <input type="text" name="description" value="<?= htmlspecialchars($resource['description'] ?? '') ?>" required>
        <button type="submit">Update</button>
    </form>
    <a href="index.php">Back</a>
</body>
</html>
