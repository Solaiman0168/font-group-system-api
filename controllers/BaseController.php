<?php

class BaseController {
    // Send response with data
    public function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        return json_encode(["status" => "success", "data" => $data]);
    }

    // Send error response
    public function sendError($message, $statusCode = 400) {
        http_response_code($statusCode);
        return json_encode(["status" => "error", "message" => $message]);
    }
}

?>
