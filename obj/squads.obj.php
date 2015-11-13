<?php

// @author Alan Thom (1103803)
// A class to represent a Squad object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Squads {

    // ***** PROPERTIES *****    
    private $id, $squad, $description, $coach;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = -1) {
        $this->id = $id;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getSquad() {
        return $this->squad;
    }    
    public function getDescription() {
        return $this->description;
    }    
    public function getCoach() {
        return $this->coach;
    }
    
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }    
    public function setSquad($squad) {
        $this->squad = $squad;
    }    
    public function setDescription($description) {
        $this->description = $description;
    }  
    public function setCoach($coach) {
        $this->coach = $coach;
    }
        
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {
        $sql = "SELECT * FROM squads WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            // Iterate through the results and set the details
            foreach ($results as $row) {
                $this->setID($row["id"]);
                $this->setSquad($row["squad"]);
                $this->setDescription($row["description"]);
                $this->setCoach($row["coach"]);
            }
            return true;
        } catch (PDOException $e) {            
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAllSquads($conn) {
        $sql = 'SELECT id, squad FROM squads';        
        $stmt = $conn->prepare($sql);        
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                $array[$result["id"]] = $result["squad"];
            }
            
            return $array;
        } catch (PDOException $e) {            
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listSquadsForSession($conn, $dayID, $venueID, $time) {        
        $sql = "SELECT s.id, s.squad FROM timetable t, squads s WHERE t.time = :time AND t.dayID = :dayID AND t.venueID = :venueID  AND t.squadID = s.id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dayID', $dayID, PDO::PARAM_INT);
        $stmt->bindParam(':venueID', $venueID, PDO::PARAM_INT);
        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                array_push($array,$result["id"]);
            }
            return $array;
        } catch (PDOException $e) {            
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAllSquadsWithParam($conn, $squad='', $description='', $coach='') {
        try {
            // To return the Squad ID plus a count of how many members it has
            $sql = "SELECT s.id, COUNT(*) FROM squads s LEFT JOIN members m ON s.id = m.squadid";
            if (!empty($squad)) {
               $sql .= " WHERE LOWER(s.squad) LIKE :squad";
            }
            if (!empty($description)) {
                if (strpos($sql,'WHERE') !== false) {
                    $sql .= " AND LOWER(s.description) LIKE :description";
                } else {
                    $sql .= " WHERE LOWER(s.description) LIKE :description";
                }
            }
            if (!empty($coach)) {
                if (strpos($sql,'WHERE') !== false) {
                    $sql .= " AND LOWER(s.coach) = :coach";
                } else {
                    $sql .= " AND LOWER(s.coach) = :coach";
                }
            }
            
            $sql .= " GROUP BY (s.id)";
            
            $stmt = $conn->prepare($sql);              
            if (!empty($squad)) {
               $stmt->bindParam(':squad', htmlentities($squad), PDO::PARAM_STR);
            }
            if (!empty($description)) {
               $stmt->bindParam(':description', htmlentities($description), PDO::PARAM_STR);
            }
            if (!empty($coach)) {
               $stmt->bindParam(':coach', htmlentities($coach), PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }
    
    public function create($conn) {
        $sql = "INSERT INTO squads (squad, description, coach) VALUES (:squad, :description, :coach)";
            
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':squad', $this->getSquad(), PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
        $stmt->bindParam(':coach', $this->getCoach(), PDO::PARAM_STR);
        
        try {
            $stmt->execute();
            $this->setID($conn->lastInsertId());
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    }
    
    public function update($conn) {
        $sql = "UPDATE squads SET squad = :squad, description = :description, coach = :coach WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
        $stmt->bindParam(':squad', $this->getSquad(), PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
        $stmt->bindParam(':coach', $this->getCoach(), PDO::PARAM_STR);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Update failed: " . $e->getMessage();
        }
    }

    public function delete($conn) {
        $sql = "DELETE FROM squads WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Update failed: " . $e->getMessage();
        }
    }
    
    public function isInputValid($squad, $description, $coach) {
        if ($this->isSquadValid($squad) && $this->isDescriptionValid($description) && $this->isCoachValid($coach)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isSquadValid($squad) {
        if (strlen($squad) > 0 && strlen($squad) <= 50) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isDescriptionValid($description) {
        if (strlen($description) <= 250) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isCoachValid($coach) {
        if (strlen($coach) > 0) {
            return true;
        } else {
            return false;
        }
    }
}



?>
