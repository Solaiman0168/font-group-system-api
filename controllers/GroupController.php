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

    // Create a new group
    public function create($name) {
        $this->group->name = $name;

        if ($this->group->create()) {
            return $this->sendResponse("Group created successfully.", 201);
        }
        return $this->sendError("Unable to create group.", 400);
    }

    // Read all groups
    public function read() {
        $groups = $this->group->read();
        return $this->sendResponse($groups);
    }

    // Read a single group by ID
    public function readOne($id) {
        $this->group->id = $id;
        $group = $this->group->readOne();

        if ($group) {
            return $this->sendResponse($group);
        }
        return $this->sendError("Group not found.", 404);
    }

    // Update a group
    public function update($id, $name) {
        $this->group->id = $id;
        $existingGroup = $this->group->readOne();

        if (!$existingGroup) {
            return $this->sendError("Group not found.", 404);
        }

        $this->group->name = $name;

        if ($this->group->update()) {
            return $this->sendResponse("Group updated successfully.", 200);
        }
        return $this->sendError("Unable to update group.", 400);
    }

    // Delete a group
    public function delete($id) {
        $this->group->id = $id;
        $group = $this->group->readOne();

        if (!$group) {
            return $this->sendError("Group not found.", 404);
        }

        if ($this->group->delete()) {
            return $this->sendResponse("Group deleted successfully.", 200);
        }
        return $this->sendError("Unable to delete group.", 400);
    }
}
?>
