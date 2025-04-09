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

    public function create() {
        $query = "INSERT INTO `{$this->table}` (name, file_path, created_at) VALUES (:name, :file_path, NOW())";
        $stmt = $this->db->prepare($query);
    
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":file_path", $this->file_path);
    
        if ($stmt->execute()) {
            $lastInsertedId = $this->db->lastInsertId();
    
            $data = [
                'id' => $lastInsertedId,
                'name' => $this->name,
                'file_path' => $this->file_path,
            ];
    
            return [
                'status' => 'success',
                'data' => [$data] 
            ];
        }
    
        return [
            'status' => 'error',
            'message' => 'Unable to create font in the database.',
        ];
    }
    

    public function read() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne() {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
