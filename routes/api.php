<?php
namespace Routes;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/FontController.php';
require_once __DIR__ . '/../controllers/GroupController.php';

use Controllers\FontController;
use Controllers\GroupController;


// // Allow CORS for the frontend running on localhost:5173
// header("Access-Control-Allow-Origin: http://localhost:5173");
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");

// // Handle pre-flight OPTIONS request (for methods like PUT, DELETE)
// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//     header("Access-Control-Allow-Origin: http://localhost:5173");
//     header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
//     header("Access-Control-Allow-Headers: Content-Type, Authorization");
//     exit(0); // End pre-flight request handling
// }

// Allow requests from all origins (for development purposes)
header("Access-Control-Allow-Origin: *"); // Allow all domains
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS"); // Allow these HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow these headers

// Handle preflight OPTIONS request (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}


// Normalize request URI
$requestUri = str_replace('/font-group-system-api', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestMethod = $_SERVER['REQUEST_METHOD'];

$groupController = new GroupController();
$fontController = new FontController();

// Debug request details
// echo json_encode(["method" => $requestMethod, "uri" => $requestUri]);

// Font routes
if ($requestMethod === 'POST' && $requestUri === '/createFont') {
    echo json_encode(["received" => $_POST, "files" => $_FILES]); // Debug input data
    // echo "<pre>"; print_r($_FILES); echo "</pre>"; die;
    echo $fontController->create($_FILES['file_path']);
} elseif ($requestMethod === 'GET' && $requestUri === '/getFonts') {
    echo $fontController->read();
} 
// elseif ($requestMethod === 'GET' && isset($_GET['id']) && $requestUri === '/getFont') {
//     echo $fontController->readOne($_GET['id']);
// } 
elseif ($requestMethod === 'DELETE' && isset($_GET['id']) && $requestUri === '/deleteFont') {
    echo $fontController->delete($_GET['id']);
} 
// elseif ($requestMethod === 'PUT' && isset($_GET['id']) && $requestUri === '/updateFont') {
//     // Assuming you send a JSON body for update
//     $inputData = json_decode(file_get_contents("php://input"), true);
//     echo $fontController->update($_GET['id'], $inputData['name'], $inputData['file_path']);
// }

// Group routes
if ($requestMethod === 'POST' && $requestUri === '/createGroup') {
    echo json_encode(["received" => $_POST]); // Debug input data
    echo $groupController->create($_POST);
} elseif ($requestMethod === 'GET' && $requestUri === '/getGroups') {
    echo $groupController->read();
} elseif ($requestMethod === 'GET' && isset($_GET['id']) && $requestUri === '/getGroup') {
    echo $groupController->readOne($_GET['id']); 
} elseif ($requestMethod === 'DELETE' && isset($_GET['id']) && $requestUri === '/deleteGroup') {
    echo $groupController->delete($_GET['id']);
} elseif ($requestMethod === 'PUT' && isset($_GET['id']) && $requestUri === '/updateGroup') {
   $inputData = json_decode(file_get_contents("php://input"), true);
//    echo json_encode(["id" => $_GET['id'], "inputData" => $inputData]); die;
   echo $groupController->update($_GET['id'], $inputData); // Pass the group ID and input data
}

?>
