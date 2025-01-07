<?php
$apiUrl = "http://localhost/ict651-project/api/resources";
$id = $_GET['id'] ?? '';
$response = json_decode(file_get_contents("$apiUrl/$id"), true);
$resource = $response['data'] ?? [];

// NYT Book Review API Integration
$nytApiKey = "8eAWpTFZd7AGEkFYGmRIDih5t89NR1w7"; // Replace with your NYT API key
$nytBooksUrl = "https://api.nytimes.com/svc/books/v3/reviews.json?title=" . urlencode($resource['name']) . "&api-key=$nytApiKey";
$nytResponse = json_decode(file_get_contents($nytBooksUrl), true);
$nytReviews = $nytResponse['results'] ?? [];
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

    <h2>NYT Book Reviews</h2>
    <?php if (!empty($nytReviews)): ?>
        <ul>
            <?php foreach ($nytReviews as $review): ?>
                <li>
                    <strong>Book Title:</strong> <?= htmlspecialchars($review['book_title'] ?? 'N/A') ?><br>
                    <strong>Author:</strong> <?= htmlspecialchars($review['book_author'] ?? 'N/A') ?><br>
                    <strong>Review Summary:</strong> <?= htmlspecialchars($review['summary'] ?? 'N/A') ?><br>
                    <strong>Review Link:</strong> <a href="<?= htmlspecialchars($review['url'] ?? '#') ?>" target="_blank">Read Full Review</a>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reviews found for this book.</p>
    <?php endif; ?>
</body>
</html>
