<?php

// @author Alan Thom (1103803)
// A class to represent a Status object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Status {

    // ***** PROPERTIES *****    
    private $id, $status, $description;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = -1) {
        $this->id = $id;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getStatus() {
        return $this->status;
    }   
    public function getDescription() {
        return $this->description;
    }  
    
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setStatus($status) {
        $this->status = $status;
    }    
    public function setDescription($description) {
        $this->description = $description;
    }   
        
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {
        
        $sql = "SELECT * FROM status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            // Iterate through the results and set the details
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setStatus($row["status"]);   
                $this->setDescription($row["description"]);   
            }
            return $results;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAllStatus($conn) {
        $sql = "SELECT id, status FROM status";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                $array[$result["id"]] = $result["status"];
            }
            
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }
}

?>