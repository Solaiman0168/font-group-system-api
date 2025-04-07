<?php
namespace Models;

use PDO;
use Config\Database;

class Font {
    private $db;
    private $table = "fonts";

    public $id;
    public $name;
    public $file_path;
    public $created_at;

    public function __construct() {
        $this->db = Database::getConnection(); 
    }

    // Create a new font record
    public function create() {
        // Prepare the query to insert the font
        $query = "INSERT INTO `{$this->table}` (name, file_path, created_at) VALUES (:name, :file_path, NOW())";
        $stmt = $this->db->prepare($query);
    
        // Bind the parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":file_path", $this->file_path);
    
        // Execute the statement
        if ($stmt->execute()) {
            // After insertion, get the last inserted ID
            $lastInsertedId = $this->db->lastInsertId();
    
            // Prepare the response data to return
            $data = [
                'id' => $lastInsertedId,
                'name' => $this->name,
                'file_path' => $this->file_path,
            ];
    
            // Return the response with the newly created font's data
            return [
                'status' => 'success',
                'data' => [$data] // Return data as an array
            ];
        }
    
        return [
            'status' => 'error',
            'message' => 'Unable to create font in the database.',
        ];
    }
    

    // Read all fonts
    public function read() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single font by ID
    public function readOne() {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete a font record
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
