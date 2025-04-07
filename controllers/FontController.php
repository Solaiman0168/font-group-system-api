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
    // public function create($file) {
    //     echo json_encode(["received" => $_POST, "files" => $_FILES]);
    //     $allowedTypes = ['font/ttf'];
    
    //     if (!in_array($file['type'], $allowedTypes)) {
    //         return $this->sendError("Only TTF files are allowed.", 400);
    //     }
    
    //     // Extract the file name without the extension
    //     $parts = explode(".", basename($file["name"]));
    //     array_pop($parts); 
    //     $fileName = implode(".", $parts); // Get the name without the extension
    
    //     $targetDir = __DIR__ . "/../uploads/fonts/";
    
    //     if (!is_dir($targetDir)) {
    //         mkdir($targetDir, 0777, true);
    //     }
    
    //     // Generate a unique name for the file and append the original extension
    //     $uniqueFileName = uniqid() . "-" . basename($file["name"]);
    
    //     // Store only the file name (not the full path) in the database
    //     $this->font->name = $fileName;
    //     $this->font->file_path = $uniqueFileName;
    
    //     // Move the uploaded file to the target directory
    //     if (move_uploaded_file($file["tmp_name"], $targetDir . $uniqueFileName)) {
    //         // Now save only the file name in the database
    //         if ($this->font->create()) {
    //             return $this->sendResponse("Font created successfully.", 201);
    //         } else {
    //             // Delete the uploaded file if there is an error in saving the database
    //             unlink($targetDir . $uniqueFileName);
    //             return $this->sendError("Unable to create font in the database.", 400);
    //         }
    //     } else {
    //         return $this->sendError("Failed to upload the font file.", 400);
    //     }
    // }


    public function create($file) {

        // Extract the file name without the extension
        $parts = explode(".", basename($file["name"]));
        array_pop($parts); 
        $fileName = implode(".", $parts); // Get the name without the extension
    
        $targetDir = __DIR__ . "/../uploads/fonts/";
    
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
    
        // Generate a unique name for the file and append the original extension
        $uniqueFileName = uniqid() . "-" . basename($file["name"]);
    
        // Store only the file name (not the full path) in the database
        $this->font->name = $fileName;
        $this->font->file_path = $uniqueFileName;
    
        // Move the uploaded file to the target directory
        if (move_uploaded_file($file["tmp_name"], $targetDir . $uniqueFileName)) {
            $fontInfo = $this->font->create();
            // echo json_encode(["fontInfo" => $fontInfo]); die;
            if ($fontInfo['status'] === 'success') {
                return $this->sendResponse($fontInfo, 201);
            } else {
                // Delete the uploaded file if there is an error in saving the database
                unlink($targetDir . $uniqueFileName);
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
