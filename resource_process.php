<?php
$apiUrl = "http://localhost/ict651-project-demo/api/resources";
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$data = $_POST;

switch ($action) {
    case 'create':
        callAPI('POST', $apiUrl, $data);
        break;
    case 'update':
        callAPI('PUT', "$apiUrl/$id", $data);
        break;
    case 'delete':
        callAPI('DELETE', "$apiUrl/$id");
        break;
}

// Redirect back to index.php
header('Location: index.php');
exit();

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
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($curl);
    curl_close($curl);
}
