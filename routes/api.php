<?php
namespace Routes;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/FontController.php';
require_once __DIR__ . '/../controllers/GroupController.php';

use Controllers\FontController;
use Controllers\GroupController;

// Allow CORS for all origins (or use the specific localhost address as required)
// Add this at the beginning of your PHP file to handle CORS for static files
header("Access-Control-Allow-Origin: *");  // Allow CORS for all origins or replace with a specific origin

// If you are serving static files (like fonts), ensure the correct MIME type and CORS header is set.
if (isset($_GET['font_file'])) {
    $fontFilePath = __DIR__ . '/uploads/fonts/' . $_GET['font_file'];  // Assuming the font file is passed in the query string as font_file

    if (file_exists($fontFilePath)) {
        // Set the appropriate content type for fonts
        header("Content-Type: font/ttf"); // or 'application/x-font-ttf' for ttf fonts
        
        // Set CORS headers
        header("Access-Control-Allow-Origin: *");  // Adjust as necessary

        // Read and serve the font file
        readfile($fontFilePath);
        exit;
    } else {
        header("HTTP/1.1 404 Not Found");
        echo "Font file not found.";
    }
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle pre-flight OPTIONS request (for methods like PUT, DELETE)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");  // Adjust the allowed origins here if required
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0); // End pre-flight request handling
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
    // echo json_encode(["received" => $_POST, "files" => $_FILES]); // Debug input data
    echo $fontController->create($_FILES['file']);
} elseif ($requestMethod === 'GET' && $requestUri === '/getFonts') {
    echo $fontController->read();
} 
elseif ($requestMethod === 'GET' && isset($_GET['id']) && $requestUri === '/getFont') {
    echo $fontController->readOne($_GET['id']);
} 
elseif ($requestMethod === 'DELETE' && isset($_GET['id']) && $requestUri === '/deleteFont') {
    echo $fontController->delete($_GET['id']);
} 

// Group routes
if ($requestMethod === 'POST' && $requestUri === '/createGroup') {
    echo $groupController->create($_POST);
} elseif ($requestMethod === 'GET' && $requestUri === '/getGroups') {
    echo $groupController->read();
} elseif ($requestMethod === 'GET' && isset($_GET['id']) && $requestUri === '/getGroup') {
    echo $groupController->readOne($_GET['id']); 
} elseif ($requestMethod === 'DELETE' && isset($_GET['id']) && $requestUri === '/deleteGroup') {
    echo $groupController->delete($_GET['id']);
} elseif ($requestMethod === 'PUT' && isset($_GET['id']) && $requestUri === '/updateGroup') {
   $inputData = json_decode(file_get_contents("php://input"), true);
   echo $groupController->update($_GET['id'], $inputData); // Pass the group ID and input data
}

?>
