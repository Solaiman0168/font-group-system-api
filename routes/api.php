<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/FontController.php';
require_once __DIR__ . '/../controllers/GroupController.php';

use Controllers\FontController;
use Controllers\GroupController;

$groupController = new GroupController();
$fontController = new FontController();

// Get the request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST' && $requestUri === '/createGroup') {
    echo $groupController->create($_POST['name']);
} elseif ($requestMethod === 'GET' && $requestUri === '/getGroups') {
    echo $groupController->read();
} elseif ($requestMethod === 'DELETE' && isset($_GET['id']) && $requestUri === '/deleteGroup') {
    echo $groupController->delete($_GET['id']);
}

if ($requestMethod === 'POST' && $requestUri === '/createFont') {
    echo $fontController->create($_POST['name'], $_FILES['file']);
} elseif ($requestMethod === 'GET' && $requestUri === '/getFonts') {
    echo $fontController->read();
} elseif ($requestMethod === 'DELETE' && isset($_GET['id']) && $requestUri === '/deleteFont') {
    echo $fontController->delete($_GET['id']);
}
?>
