<?php
namespace Models;

use PDO;
use Config\Database;

class Group
{
    private $db;
    private $table = 'groups';

    public $id;
    public $name;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Create a new group
    public function create()
    {
        $query = "INSERT INTO {$this->table} (name, created_at, updated_at) VALUES (:name, NOW(), NOW())";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $this->name);

        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();
            return true;
        }
        return false;
    }

    // Read all groups
    public function read()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single group by ID
    public function readOne()
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a group
    public function update()
    {
        $query = "UPDATE {$this->table} 
                  SET name = :name, updated_at = NOW() 
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a group
    public function delete()
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
?>
