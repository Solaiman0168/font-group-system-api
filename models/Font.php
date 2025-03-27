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
    public $updated_at;

    public function __construct() {
        $this->db = Database::getConnection(); 
    }

    // Create a new font record
    public function create() {
        $query = "INSERT INTO {$this->table} (name, file_path, created_at, updated_at) 
                  VALUES (:name, :file_path, NOW(), NOW())";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":file_path", $this->file_path);

        return $stmt->execute();
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

    // Update a font record
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET name = :name, file_path = :file_path, updated_at = NOW() 
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":file_path", $this->file_path);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
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
