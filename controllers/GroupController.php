<?php

namespace Controllers;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/BaseController.php';

use Models\Group; // Import the Group class

class GroupController extends BaseController {
    private $group;

    public function __construct() {
        $this->group = new Group(); // No need to pass $db because Group class already initializes Database::getConnection()
    }

    public function create($name) {
        $this->group->name = $name;

        if ($this->group->create()) {
            return $this->sendResponse("Group created successfully.", 201);
        }
        return $this->sendError("Unable to create group.", 400);
    }

    public function read() {
        $groups = $this->group->read();
        return $this->sendResponse($groups);
    }

    public function delete($id) {
        $this->group->id = $id;

        if ($this->group->delete()) {
            return $this->sendResponse("Group deleted successfully.", 200);
        }
        return $this->sendError("Unable to delete group.", 400);
    }
}

?>
