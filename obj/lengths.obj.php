<?php

// @author Alan Thom (1103803)
// A class to represent a Length object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Lengths {

    // ***** PROPERTIES *****    
    private $id, $length;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = -1) {
        $this->id = $id;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getLength() {
        return $this->length;
    }    
    
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setLength($length) {
        $this->length = $length;
    }    
        
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {
        
        $sql = "SELECT * FROM lengths WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setLength($row["length"]);                
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }

    public function listAllLengths($conn) {
        $sql = 'SELECT id, length FROM lengths ORDER BY id ASC';        
        $stmt = $conn->prepare($sql);        
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                $array[$result["id"]] = $result["length"];
            }
            
            return $array;
        } catch (PDOException $e) {            
            return "Query failed: " . $e->getMessage();
        }    
    }
}

?>
