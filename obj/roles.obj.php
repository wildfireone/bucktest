<?php

// @author Alan Thom (1103803)
// A class to represent a Role object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Roles {

    // ***** PROPERTIES *****    
    private $id, $role, $description, $email;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = -1) {
        $this->id = $id;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getRole() {
        return $this->role;
    } 
    public function getDescription() {
        return $this->description;
    } 
    public function getEmail() {
        return $this->email;
    } 
    
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setRole($role) {
        $this->role = $role;
    }    
    public function setDescription($description) {
        $this->description = $description;
    }  
    public function setEmail($email) {
        $this->email = $email;
    }    
        
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {
        
        $sql = "SELECT * FROM roles WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setRole($row["role"]); 
                $this->setDescription($row["description"]); 
                $this->setEmail($row["email"]);  
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAllRoles($conn) {
        $sql = "SELECT id, role FROM roles";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                $array[$result["id"]] = $result["role"];
            }
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }
    
    public function isInputValid($role, $description) {
        if (isRoleValid($role) && isDescriptionValid($description)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isRoleValid($role) {
        if (count($role) > 0 && count($role) <= 250) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isDescriptionValid($description) {
        if (count($description) <= 250) { 
            return true;
        } else {
            return false;
        }
    }
    
    public function doesExist($conn) {
        $sql = "SELECT id FROM roles WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            if (count($results == 1)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }
}

?>
