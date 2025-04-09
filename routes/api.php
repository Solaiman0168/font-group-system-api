<?php
namespace Routes;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/FontController.php';
require_once __DIR__ . '/../controllers/GroupController.php';

use Controllers\FontController;
use Controllers\GroupController;

header("Access-Control-Allow-Origin: *");  

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");  
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0); // End pre-flight request handling
}


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
