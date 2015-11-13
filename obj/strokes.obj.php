<?php

// @author Alan Thom (1103803)
// A class to represent a Stroke object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Strokes {

    // ***** PROPERTIES *****    
    private $id, $stroke;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = -1) {
        $this->id = $id;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getStroke() {
        return $this->stroke;
    }    
    
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setStroke($stroke) {
        $this->stroke = $stroke;
    }    
        
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {
        
        $sql = "SELECT * FROM strokes WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setStroke($row["stroke"]);                
            }
            return true;
        } catch (PDOException $e) {
            dbClose($conn);
            return "Query failed: " . $e->getMessage();
        }    
    }

    public function listAllStrokes($conn) {
        $sql = 'SELECT id, stroke FROM strokes ORDER BY stroke ASC';        
        $stmt = $conn->prepare($sql);        
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                $array[$result["id"]] = $result["stroke"];
            }
            
            return $array;
        } catch (PDOException $e) {            
            return "Query failed: " . $e->getMessage();
        }    
    }

    public function listAllStrokesForSwimmer($conn, $member) {
        $sql = 'SELECT DISTINCT e.strokeID FROM swim_times s, gala_events e WHERE s.eventID = e.id AND s.member = :member ORDER BY strokeID ASC';        
        $stmt = $conn->prepare($sql);       

        $stmt->bindParam(':member', $member, PDO::PARAM_STR); 
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            return $results;
        } catch (PDOException $e) {            
            return "Query failed: " . $e->getMessage();
        }    
    }  
  
}

?>
