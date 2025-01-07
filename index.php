<?php
// API URL
$apiUrl = "http://localhost/ict651-project-demo/api/resources";

// Service Consumer Function
function callAPI($method, $url, $data = false) {
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, true);
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        default:
            if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $result = curl_exec($curl);

    if (!$result) die("Connection Failure");
    curl_close($curl);
    return $result;
}

// Fetch resources
$response = json_decode(callAPI('GET', $apiUrl), true);
$resources = $response['data'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Management</title>
</head>
<body>
    <h1>Resource Management</h1>

    <h2>Create Resource</h2>
    <form method="POST" action="resource_process.php?action=create">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="description" placeholder="Description" required>
        <button type="submit">Create</button>
    </form>

    <h2>Resource List</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($resources)): ?>
                <?php foreach ($resources as $resource): ?>
                    <tr>
                        <td><?= htmlspecialchars($resource['id']) ?></td>
                        <td><?= htmlspecialchars($resource['name']) ?></td>
                        <td><?= htmlspecialchars($resource['description']) ?></td>
                        <td>
                            <a href="resource_view.php?id=<?= htmlspecialchars($resource['id']) ?>">View</a> | 
                            <a href="resource_nyt_book_view.php?id=<?= htmlspecialchars($resource['id']) ?>">View (NYT)</a> | 
                            <a href="resource_google_book_view.php?id=<?= htmlspecialchars($resource['id']) ?>">View (google)</a> | 
                            <a href="resource_update.php?id=<?= htmlspecialchars($resource['id']) ?>">Update</a> | 
                            <a href="resource_delete.php?id=<?= htmlspecialchars($resource['id']) ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No resources found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
