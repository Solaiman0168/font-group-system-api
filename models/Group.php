<?php
namespace Models;

use PDO;
use Config\Database;

class Group
{
    private $db;
    private $table = "groups";

    public $id;
    public $title;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getDb()
    {
        return $this->db; // This will allow access to the private $db property
    }

    // Create a new group
    public function create()
    {
        $query = "INSERT INTO `{$this->table}` (title, created_at, updated_at) VALUES (:title, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $this->title);

        return $stmt->execute(); // Execute the insertion query
    }


    public function isGroupTitleExists($title) {
        $query = "SELECT COUNT(*) FROM `{$this->table}` WHERE title = :title";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    // Read all groups
    // public function read()
    // {
    //     $query = "SELECT * FROM `{$this->table}` ORDER BY created_at DESC";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }


    // Read all groups along with their full font details
    public function readWithFonts()
    {
        $query = "SELECT g.id, g.title, g.created_at, g.updated_at, f.id as font_id, f.name as font_name, f.file_path as font_file_path, f.created_at as font_created_at
                FROM `{$this->table}` g
                LEFT JOIN `group_fonts` gf ON g.id = gf.group_id
                LEFT JOIN `fonts` f ON gf.font_id = f.id
                ORDER BY g.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Structure the data: group with its fonts
        $groupData = [];
        foreach ($groups as $group) {
            if (!isset($groupData[$group['id']])) {
                $groupData[$group['id']] = [
                    'id' => $group['id'],
                    'title' => $group['title'],
                    'created_at' => $group['created_at'],
                    'updated_at' => $group['updated_at'],
                    'fonts' => []
                ];
            }
            if ($group['font_id']) {
                $groupData[$group['id']]['fonts'][] = [
                    'id' => $group['font_id'],
                    'name' => $group['font_name'],
                    'file_path' => $group['font_file_path'],
                    'created_at' => $group['font_created_at']
                ];
            }
        }

        return array_values($groupData); // Convert associative array to indexed array
    }




    public function readOne($id)
    {
        // $query = "SELECT * FROM `{$this->table}` WHERE id = :id"; // Make sure the table name is properly escaped with backticks
        $query = "SELECT g.id, g.title, g.created_at, g.updated_at, f.id as font_id, f.name as font_name, f.file_path as font_file_path
        FROM `{$this->table}` g
        LEFT JOIN `group_fonts` gf ON g.id = gf.group_id
        LEFT JOIN `fonts` f ON gf.font_id = f.id
        WHERE g.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id); // Bind the id parameter
        $stmt->execute();
    
        $groupInfo = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the result
        // echo json_encode(["groupInfo" => $groupInfo]);
        $groupData = [];
        if (!isset($groupData[$groupInfo['id']])) {
            $groupData[$groupInfo['id']] = [
                'id' => $groupInfo['id'],
                'title' => $groupInfo['title'],
                'created_at' => $groupInfo['created_at'],
                'updated_at' => $groupInfo['updated_at'],
                'fonts' => []
            ];
        }
        
        if ($groupInfo['font_id']) {
            $groupData[$groupInfo['id']]['fonts'][] = [
                'id' => $groupInfo['font_id'],
                'name' => $groupInfo['font_name'],
                'file_path' => $groupInfo['font_file_path']
            ];
        }
        return array_values($groupData);
        // return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the result
    }


    // Update a group
    public function update()
    {
        $query = "UPDATE `{$this->table}` 
                  SET title = :title, updated_at = NOW() 
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a group
    public function delete()
    {
        $query = "DELETE FROM `{$this->table}` WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
?>
