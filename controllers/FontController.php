<?php

namespace Controllers;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Font.php';
require_once __DIR__ . '/BaseController.php';

use Models\Font;

class FontController extends BaseController {
    private $font;

    public function __construct() {
        $this->font = new Font(); // ✅ No need to pass `$db`
    }

    public function create($name, $file) {
        $allowedTypes = ['font/ttf'];
        $maxFileSize = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) {
            return $this->sendError("Only TTF files are allowed.", 400);
        }

        if ($file['size'] > $maxFileSize) {
            return $this->sendError("File size exceeds the maximum allowed size of 5MB.", 400);
        }

        $targetDir = __DIR__ . "/../uploads/fonts/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filePath = $targetDir . uniqid() . "-" . basename($file["name"]);

        if (move_uploaded_file($file["tmp_name"], $filePath)) {
            $this->font->name = $name;
            $this->font->file_path = $filePath;

            if ($this->font->create()) {
                return $this->sendResponse("Font created successfully.", 201);
            } else {
                unlink($filePath);
                return $this->sendError("Unable to create font in the database.", 400);
            }
        } else {
            return $this->sendError("Failed to upload the font file.", 400);
        }
    }

    public function read() {
        $fonts = $this->font->read();
        return $this->sendResponse($fonts);
    }

    public function delete($id) {
        $this->font->id = $id;

        if ($this->font->delete()) {
            return $this->sendResponse("Font deleted successfully.", 200);
        }
        return $this->sendError("Unable to delete font.", 400);
    }
}
?>
