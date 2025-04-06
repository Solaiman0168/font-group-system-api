<?php 
namespace Models;

use PDO;
use Config\Database;

class GroupFont {
    private $db;
    private $table = 'group_fonts';

    public $id;
    public $group_id;
    public $font_id;
    public $created_at;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create() 
    {
        $query = "INSERT INTO `{$this->table}` (group_id, font_id, created_at) VALUES (:group_id, :font_id, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':group_id', $this->group_id);
        $stmt->bindParam(':font_id', $this->font_id);
        return $stmt->execute();
    }

    public function fontExists($font_id)
    {
        $query = "SELECT COUNT(*) FROM `fonts` WHERE `id` = :font_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':font_id', $font_id);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0; // Returns true if font_id exists, otherwise false
    }

    public function deleteByGroupId($group_id) {
        $query = "DELETE FROM `group_fonts` WHERE group_id = :group_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':group_id', $group_id);
        return $stmt->execute();
    }
    

}

?>