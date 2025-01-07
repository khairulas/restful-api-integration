<?php
$apiUrl = "http://localhost/ict651-project-demo/api/resources";
$id = $_GET['id'] ?? '';
$response = json_decode(file_get_contents("$apiUrl/$id"), true);
$resource = $response['data'] ?? [];

// Google Books API Integration
$googleApiKey = "AIzaSyDD6XpRuE0dJSDMGqf-a2m9-SPHvM3EGbE"; // Replace with your API key
$googleBooksUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($resource['name']);
$googleResponse = json_decode(file_get_contents($googleBooksUrl), true);
$googleBooks = $googleResponse['items'] ?? [];
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

    <h2>Related Book Reviews (Google Books)</h2>
    <?php if (!empty($googleBooks)): ?>
        <ul>
            <?php foreach ($googleBooks as $book): ?>
                <li>
                    <strong>Title:</strong> <?= htmlspecialchars($book['volumeInfo']['title'] ?? 'N/A') ?><br>
                    <strong>Authors:</strong> <?= htmlspecialchars(implode(', ', $book['volumeInfo']['authors'] ?? [])) ?><br>
                    <strong>Description:</strong> <?= htmlspecialchars($book['volumeInfo']['description'] ?? 'N/A') ?><br>
                    <strong>Link:</strong> <a href="<?= htmlspecialchars($book['volumeInfo']['infoLink'] ?? '#') ?>" target="_blank">View on Google Books</a>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No related reviews found.</p>
    <?php endif; ?>
</body>
</html>
