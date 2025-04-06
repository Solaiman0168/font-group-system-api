<?php

namespace Controllers;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../models/GroupFont.php';
require_once __DIR__ . '/BaseController.php';

use Models\Group; // Import the Group class
use Models\GroupFont; // Import the GroupFont class

class GroupController extends BaseController {
    private $group;
    private $groupFont;

    public function __construct() {
        $this->group = new Group(); 
        $this->groupFont = new GroupFont();
    }

    // Create a new group
    public function create($request) {
        // Set the title for the group
        $this->group->title = $request['title'];
    
        $isGroupTitleExists = $this->group->isGroupTitleExists($this->group->title);
        if ($isGroupTitleExists) {
            return $this->sendError("Group with title {$this->group->title} already exists.", 400);
        }
        // Create the group
        if ($this->group->create()) {
            // After creating the group, get the ID of the newly created group
            $this->group->id = $this->group->getDb()->lastInsertId(); // Use the getter method
    
            // Set the group_id in GroupFont and insert associated font_ids
            $this->groupFont->group_id = $this->group->id;
    
            if (isset($request['font_ids']) && count($request['font_ids']) > 0) {
                foreach ($request['font_ids'] as $font_id) {
                    // Check if the font_id exists
                    if ($this->groupFont->fontExists($font_id)) {
                        $this->groupFont->font_id = $font_id;
                        // Create the record in the GroupFont table
                        $this->groupFont->create();
                    } else {
                        return $this->sendError("Font with ID {$font_id} does not exist.", 400);
                    }
                }
            }
    
            return $this->sendResponse("Group created successfully.", 201);
        }
    
        return $this->sendError("Unable to create group.", 400);
    }
    
    
    
    
    

    // Read all groups
    // public function read() {
    //     $groups = $this->group->read();
    //     return $this->sendResponse($groups);
    // }

    // Read all groups along with their fonts
    public function read() {
        $groups = $this->group->readWithFonts();  // Call the method to fetch groups with fonts
        return $this->sendResponse($groups);
    }

    // Read a single group by ID
    public function readOne($id) {
        // Pass the id to the Group model's readOne method
        $this->group->id = $id; // Ensure group ID is set in the model
        $group = $this->group->readOne($id); // Pass the id correctly to the model
    
        if ($group) {
            return $this->sendResponse($group);
        }
        return $this->sendError("Group not found.", 404);
    }

    // Update a group
    public function update($id, $inputData) {
        // Set the group ID from the URL and the new title from the body
        $this->group->id = $id;
        // echo json_encode(["title" => $inputData[0]['title']]); die;
        $this->group->title = $inputData[0]['title'];
    
        // Fetch the existing group
        $existingGroup = $this->group->readOne($id);
    
        if (!$existingGroup) {
            return $this->sendError("Group not found.", 404);
        }
    
        // First, update the group title
        if (!$this->group->update()) {
            return $this->sendError("Unable to update group.", 400);
        }
    
        // Then, update the associated fonts (i.e., group_fonts table)
        // Clear existing font associations for this group
        $this->groupFont->deleteByGroupId($id);  // Assuming you have a method to delete existing font associations
    
        // Insert the new font associations
        if (isset($inputData[0]['font_ids']) && count($inputData[0]['font_ids']) > 0) {
            foreach ($inputData[0]['font_ids'] as $font_id) {
                $this->groupFont->group_id = $id;
                $this->groupFont->font_id = $font_id;
                if (!$this->groupFont->create()) {
                    return $this->sendError("Failed to associate font id {$font_id} with the group.", 400);
                }
            }
        }
    
        return $this->sendResponse("Group and fonts updated successfully.", 200);
    }

    


    // Delete a group
    public function delete($id) {
        $this->group->id = $id;
        $findGroup = $this->group->readOne($id);

        if (!$findGroup) {
            return $this->sendError("Group not found.", 404);
        }

        // First, delete the associated fonts (group_fonts records)
        $this->groupFont->deleteByGroupId($id);

        if ($this->group->delete()) {
            return $this->sendResponse("Group deleted successfully.", 200);
        }
        return $this->sendError("Unable to delete group.", 400);
    }
}
?>
