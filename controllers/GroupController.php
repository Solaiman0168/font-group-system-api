<?php

namespace Controllers;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Group.php';
require_once __DIR__ . '/../models/GroupFont.php';
require_once __DIR__ . '/BaseController.php';

use Models\Group; 
use Models\GroupFont;

class GroupController extends BaseController {
    private $group;
    private $groupFont;

    public function __construct() {
        $this->group = new Group(); 
        $this->groupFont = new GroupFont();
    }
    
    
    public function create($request) {

        $this->group->title = $request['title'];
    
        $isGroupTitleExists = $this->group->isGroupTitleExists($this->group->title);
        if ($isGroupTitleExists) {
            return $this->sendError("Group with title {$this->group->title} already exists.", 400);
        }
    

        if ($this->group->create()) {
            $this->group->id = $this->group->getDb()->lastInsertId();
    
            $this->groupFont->group_id = $this->group->id;
    
            if (isset($request['font_ids']) && count($request['font_ids']) > 0) {
                foreach ($request['font_ids'] as $font_id) {
                    if ($this->groupFont->fontExists($font_id)) {
                        $this->groupFont->font_id = $font_id;
                        $this->groupFont->create();
                    } else {
                        return $this->sendError("Font with ID {$font_id} does not exist.", 400);
                    }
                }
            }
    
            // Return the created group along with its fonts
            $groupData = $this->group->readOne($this->group->id);  
    
            return $this->sendResponse($groupData, 201);  
        }
    
        return $this->sendError("Unable to create group.", 400);
    }


    public function read() {
        $groups = $this->group->readWithFonts();  // Call the method to fetch groups with fonts
        return $this->sendResponse($groups);
    }

    // Read a single group by ID
    public function readOne($id) {
        $this->group->id = $id; 
        $group = $this->group->readOne($id); 
    
        if ($group) {
            return $this->sendResponse($group);
        }
        return $this->sendError("Group not found.", 404);
    }

    // Update a group
    public function update($id, $inputData) {
        $this->group->id = $id;
        $this->group->title = $inputData['title'];
    
        $existingGroup = $this->group->readOne($id);
    
        if (!$existingGroup) {
            return $this->sendError("Group not found.", 404);
        }
    
        if (!$this->group->update()) {
            return $this->sendError("Unable to update group.", 400);
        }
    

        // Clear existing font associations for this group
        $this->groupFont->deleteByGroupId($id);  
    
        // Insert the new font associations
        if (isset($inputData['font_ids']) && count($inputData['font_ids']) > 0) {
            foreach ($inputData['font_ids'] as $font_id) {
                $this->groupFont->group_id = $id;
                $this->groupFont->font_id = $font_id;
                if (!$this->groupFont->create()) {
                    return $this->sendError("Failed to associate font id {$font_id} with the group.", 400);
                }
            }
        }

        $groupData = $this->group->readOne($id);
    
        return $this->sendResponse($groupData, 200);
    }

    
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
