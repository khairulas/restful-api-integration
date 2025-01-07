<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'ict651_project_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Database connection
function getDatabaseConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        sendResponse(500, "Database Connection Error", ["error" => $e->getMessage()]);
    }
}

// Helper function to send a response
function sendResponse($status, $message, $data = null) {
    http_response_code($status);
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}


// Sample routing
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$pdo = getDatabaseConnection(); // Establish database connection

// Extract the clean URI
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$cleanUri = str_replace($scriptName, '', $requestUri);

switch ($requestMethod) {
    case 'GET':
        if ($cleanUri == '/api/resources') {
            fetchResources($pdo); 
        } elseif (preg_match('/^\/api\/resources\/([0-9]+)$/', $cleanUri, $matches)) { 
            fetchResourceById($pdo, intval($matches[1])); 
        } else {
            sendResponse(404, "Not Found");
        }
        break;
     

    case 'POST':
        if ($cleanUri == '/api/resources') {
            createResource($pdo);
        } else {
            sendResponse(404, "Not Found");
        }
        break;

        case 'PUT':
            if (preg_match('/^\/api\/resources\/([0-9]+)$/', $cleanUri, $matches)) {
                $id = intval($matches[1]);
                updateResource($pdo, $id);
            } else {
                sendResponse(404, "Not Found");
            }
            break;
    
        case 'DELETE':
            if (preg_match('/^\/api\/resources\/([0-9]+)$/', $cleanUri, $matches)) {
                $id = intval($matches[1]);
                deleteResource($pdo, $id);
            } else {
                sendResponse(404, "Not Found");
            }
            break;

    default:
        sendResponse(501, "Not Implemented");
}

// CRUD Operations



// Fetch resources (GET)
function fetchResources($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM resources");
        $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resources) {
            sendResponse(200, "Resources Found", $resources);
        } else {
            sendResponse(404, "No Resources Found");
        }
    } catch (PDOException $e) {
        sendResponse(500, "Error Fetching Resources", ["error" => $e->getMessage()]);
    }
}

// Fetch a single resource by ID
function fetchResourceById($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM resources WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resource = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resource) {
            sendResponse(200, "Resource Found", $resource); // Return a single object
        } else {
            sendResponse(404, "Resource Not Found");
        }
    } catch (PDOException $e) {
        sendResponse(500, "Error Fetching Resource", ["error" => $e->getMessage()]);
    }
}




// Create a new resource (POST)
function createResource($pdo) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['name']) || !isset($input['description'])) {
        sendResponse(400, "Bad Request", ["error" => "Missing required fields"]);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO resources (name, description) VALUES (:name, :description)");
        $stmt->execute([
            ':name' => $input['name'],
            ':description' => $input['description']
        ]);
        sendResponse(201, "Resource Created", ["id" => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        sendResponse(500, "Error Creating Resource", ["error" => $e->getMessage()]);
    }
}

// Update a resource (PUT)
function updateResource($pdo, $id) {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['name']) || !isset($input['description'])) {
        sendResponse(400, "Bad Request", ["error" => "Missing required fields"]);
    }

    try {
        $stmt = $pdo->prepare("UPDATE resources SET name = :name, description = :description WHERE id = :id");
        $stmt->execute([
            ':id' => $id, // Use the $id from the URL
            ':name' => $input['name'],
            ':description' => $input['description']
        ]);
       
        if ($stmt->rowCount() > 0) {
            sendResponse(200, "Resource Updated"); 
        } else {
            sendResponse(404, "Resource Not Found", ["id" => $id]);
        }
    } catch (PDOException $e) {
        sendResponse(500, "Error Updating Resource", ["error" => $e->getMessage()]);
    }
}

// Delete a resource (DELETE)
function deleteResource($pdo, $id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM resources WHERE id = :id");
        $stmt->execute([':id' => $id]); 
        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            sendResponse(200, "Resource Deleted", ["id" => $id]);
        } else {
            // If no rows were affected, the ID does not exist
            sendResponse(404, "Resource Not Found", ["id" => $id]);
        } 
    } catch (PDOException $e) {
        sendResponse(500, "Error Deleting Resource", ["error" => $e->getMessage()]);
    }
}

?>
