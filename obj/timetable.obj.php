<?php

// @author Alan Thom (1103803)
// A class to represent a Timetable object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete club records

class Timetable {

    // ***** PROPERTIES *****    
    private $dayID, $squadID, $venueID, $time;
    
    // ***** CONSTRUCTOR *****
    function __construct($dayID = -1, $squadID = -1, $venueID = -1, $time = '-1') {
        $this->dayID = $dayID;
        $this->squadID = $squadID;
        $this->venueID = $venueID;
        $this->time = $time;
    }
    
    // ***** GETTERS *****
    public function getDayID() {
        return $this->dayID;
    }
    public function getSquadID() {
        return $this->squadID;    
    }
    public function getVenueID() {
        return $this->venueID;    
    }       
    public function getTime() {
        return $this->time;
    } 
        
    // ***** SETTERS *****    
    public function setDayID($dayID) {
        $this->dayID = $dayID;
    }    
    public function setSquadID($squadID) {
        $this->squadID = $squadID;
    }  
    public function setVenueID($venueID) {
        $this->venueID = $venueID;
    }    
    public function setTime($time) {
        $this->time = $time;
    }
    
    // ***** OTHER METHODS *****    
    public function listAll($conn) {
        $sql = "SELECT * FROM timetable ORDER BY dayID";
        
        $stmt = $conn->prepare($sql);
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function getTimeSlots($conn) {
        $sql = "SELECT DISTINCT dayID, venueID, time FROM timetable ORDER BY dayID, time";
        
        $stmt = $conn->prepare($sql);
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }

    public function create($conn) {
        $sql .= "INSERT INTO timetable (squadID, dayID, venueID, time) VALUES (:squadID, :dayID, :venueID, :time)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':squadID', $this->getSquadID(), PDO::PARAM_INT);
        $stmt->bindParam(':dayID', $this->getDayID(), PDO::PARAM_INT);
        $stmt->bindParam(':venueID', $this->getVenueID(), PDO::PARAM_INT);
        $stmt->bindParam(':time', $this->getTime(), PDO::PARAM_STR);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        } 
    }

    public function delete($conn) {
        $sql = "DELETE FROM timetable WHERE dayID = :dayID AND venueID = :venueID AND time = :time";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dayID', $this->getDayID(), PDO::PARAM_INT);
        $stmt->bindParam(':venueID', $this->getVenueID(), PDO::PARAM_INT);
        $stmt->bindParam(':time', $this->getTime(), PDO::PARAM_STR);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        } 
    }
    
    public function isInputValid($squads,$day,$venue,$time) {
        if ($this->isSquadValid($squads) && $this->isDayValid($day) && $this->isVenueValid($venue) && $this->isTimeValid($time)) {
            return true;
        } else {
            return false;
        }
    }

    public function isSquadValid($squad) {
        if ($squad > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isDayValid($day) {
        if ($day >= 1 && $day <= 7) {
            return true;
        } else {
            return false;
        }
    }

    public function isVenueValid($venue) {
        if ($venue > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isTimeValid($time) {
        if (strlen($time) > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>
