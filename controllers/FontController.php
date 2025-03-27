<?php

namespace Controllers;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Font.php';
require_once __DIR__ . '/BaseController.php';

use Models\Font;

class FontController extends BaseController {
    private $font;

    public function __construct() {
        $this->font = new Font();
    }

    // Create a new font
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

    // Read all fonts
    public function read() {
        $fonts = $this->font->read();
        return $this->sendResponse($fonts);
    }

    // Read a single font by ID
    public function readOne($id) {
        $this->font->id = $id;
        $font = $this->font->readOne();

        if ($font) {
            return $this->sendResponse($font);
        }
        return $this->sendError("Font not found.", 404);
    }

    // Update a font
    public function update($id, $name, $file = null) {
        $this->font->id = $id;
        $existingFont = $this->font->readOne();

        if (!$existingFont) {
            return $this->sendError("Font not found.", 404);
        }

        $this->font->name = $name;

        // If a new file is uploaded, handle file replacement
        if ($file) {
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
                // Delete the old file
                if (file_exists($existingFont['file_path'])) {
                    unlink($existingFont['file_path']);
                }

                $this->font->file_path = $filePath;
            } else {
                return $this->sendError("Failed to upload the font file.", 400);
            }
        } else {
            $this->font->file_path = $existingFont['file_path'];
        }

        if ($this->font->update()) {
            return $this->sendResponse("Font updated successfully.", 200);
        }
        return $this->sendError("Unable to update font.", 400);
    }

    // Delete a font
    public function delete($id) {
        $this->font->id = $id;
        $font = $this->font->readOne();

        if (!$font) {
            return $this->sendError("Font not found.", 404);
        }

        // Delete the font file from the directory
        if (file_exists($font['file_path'])) {
            unlink($font['file_path']);
        }

        if ($this->font->delete()) {
            return $this->sendResponse("Font deleted successfully.", 200);
        }
        return $this->sendError("Unable to delete font.", 400);
    }
}
?>
