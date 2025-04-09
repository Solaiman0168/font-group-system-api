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

    public function create($file) {

        $parts = explode(".", basename($file["name"]));
        array_pop($parts); 
        $fileName = implode(".", $parts); 
    
        $targetDir = __DIR__ . "/../uploads/fonts/";
    
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
    
        $uniqueFileName = uniqid() . "-" . basename($file["name"]);
    
        $this->font->name = $fileName;
        $this->font->file_path = $uniqueFileName;
    
        if (move_uploaded_file($file["tmp_name"], $targetDir . $uniqueFileName)) {
            $fontInfo = $this->font->create();
            if ($fontInfo['status'] === 'success') {
                return $this->sendResponse($fontInfo['data'], 201);
            } else {
                unlink($targetDir . $uniqueFileName);
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

    public function readOne($id) {
        $this->font->id = $id;
        $font = $this->font->readOne();

        if ($font) {
            return $this->sendResponse($font);
        }
        return $this->sendError("Font not found.", 404);
    }


    public function delete($id) {
        $this->font->id = $id;
        $font = $this->font->readOne();

        if (!$font) {
            return $this->sendError("Font not found.", 404);
        }

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
