<?php

// @author Alan Thom (1103803)
// A class to represent a Timetable object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete club records

class Days {

    // ***** PROPERTIES *****    
    private $id, $day;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = -1) {
        $this->id = $id;
    }
    
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getDay() {
        return $this->day;    
    }
        
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setDay($day) {
        $this->day = $day;
    }  
    
    // ***** OTHER METHODS *****
    public function getAllDetails($conn) {
        
        $sql = "SELECT * FROM days WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setDay($row["day"]);                
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAll($conn) {
        $sql = "SELECT * FROM days";
        
        $stmt = $conn->prepare($sql);
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            $array = array();
            foreach ($results as $result) {
                $array[$result["id"]] = $result["day"];
            }
            
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
}

?>
