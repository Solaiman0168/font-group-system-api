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
        $this->db = Database::getConnection(); // ✅ Corrected to use static method
    }

    public function create() {
        $query = "INSERT INTO {$this->table} (name, file_path, created_at) VALUES (:name, :file_path, NOW())";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":file_path", $this->file_path);

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
?>
