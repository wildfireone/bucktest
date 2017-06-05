<?php

// @author Alan Thom (1103803)
// A class to represent a Swimming Time object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Swim_Times {

    // ***** PROPERTIES *****    
    private $member, $galaID, $eventID, $time, $rank;
    
    // ***** CONSTRUCTOR *****
    function __construct($member = 'zzz', $galaID = 'zzz', $eventID = 'zzz') {
        $this->member = $member;
        $this->galaID = $galaID;
        $this->eventID = $eventID;
    }
        
    // ***** GETTERS *****
    public function getMember() {
        return $this->member;
    }
    public function getGalaID() {
        return $this->galaID;
    }    
    public function getEventID() {
        return $this->eventID;
    }    
    public function getTime() {
        return $this->time;
    }     
    public function getRank() {
        return $this->rank;
    }  
    
    // ***** SETTERS *****    
    public function setMember($member) {
        $this->member = $member;
    }    
    public function setGalaID($galaID) {
        $this->galaID = $galaID;
    }    
    public function setEventID($eventID) {
        $this->eventID = $eventID;
    }    
    public function setTime($time) {
        $this->time = $time;
    }    
    public function setRank($rank) {
        $this->rank = $rank;
    }    

    public function setPKS($member, $galaID, $eventID) {
        $this->member = $member;
        $this->galaID = $galaID;
        $this->eventID = $eventID;
    }
    
    // ***** OTHER METHODS *****    
    public function getAllDetails() {
        $conn = dbConnect();
        
        $sql = "SELECT * FROM swim_times WHERE member = :member AND galaID = :galaID AND eventID = :eventID";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':member', $this->getMember(), PDO::PARAM_STR);
        $stmt->bindParam(':galaID', $this->getGalaID(), PDO::PARAM_STR);
        $stmt->bindParam(':eventID', $this->getEventID(), PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            dbClose($conn);
            
            // Iterate through the results and set the details
            foreach ($results as $row) {
                $this->setMember($row["member"]);
                $this->setGalaID($row["galaID"]);
                $this->setEventID($row["eventID"]);
                $this->setTime($row["time"]);
                $this->setRank($row["rank"]);
            }
            return true;
        } catch (PDOException $e) {
            dbClose($conn);
            return "Query failed: " . $e->getMessage();
        }    
    }

    public function create($conn) {
        $sql = "INSERT INTO swim_times (member, galaID, eventID, time, rank) VALUES (:member, :galaID, :eventID, :time, :rank)";
            $stmt = $conn->prepare($sql);
                
            $stmt->bindParam(':member', $this->getMember(), PDO::PARAM_STR);
            $stmt->bindParam(':galaID', $this->getGalaID(), PDO::PARAM_STR);
            $stmt->bindParam(':eventID', $this->getEventID(), PDO::PARAM_STR);
            $stmt->bindParam(':time', $this->getTime(), PDO::PARAM_STR);
            $stmt->bindParam(':rank', $this->getRank(), PDO::PARAM_INT);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    }

    public function delete($conn) {
        $sql = "DELETE FROM swim_times WHERE member = :member AND galaID = :galaID AND eventID = :eventID";
            $stmt = $conn->prepare($sql);
                
            $stmt->bindParam(':member', $this->getMember(), PDO::PARAM_STR);
            $stmt->bindParam(':galaID', $this->getGalaID(), PDO::PARAM_STR);
            $stmt->bindParam(':eventID', $this->getEventID(), PDO::PARAM_STR);
        
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    }

    public function listAllSwimTimesForGalaByEvent($conn, $galaID, $eventID) {       
        $sql = "SELECT member, galaID, eventID FROM swim_times WHERE galaID = '".$galaID."' AND eventID = '".$eventID."' ORDER BY time ASC";
        
        $stmt = $conn->prepare($sql); 
        //$stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        //$stmt->bindParam(':eventID', $eventID, PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllSwimTimesForGalaByEventAndSwimmer($conn, $galaID, $eventID, $member) {       
        $sql = "SELECT member, galaID, eventID FROM swim_times WHERE galaID = :galaID AND eventID = :eventID AND member = :member ORDER BY time ASC";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        $stmt->bindParam(':eventID', $eventID, PDO::PARAM_STR);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllSwimTimesForSwimmer($conn, $member) {       
        $sql = "SELECT s.member, s.galaID, s.eventID FROM swim_times s, galas g WHERE s.member = :member AND s.galaID = g.id ORDER BY g.date DESC";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllPBsForSwimmerByStroke($conn, $member, $stroke) {       
        $sql = "SELECT t.member, t.galaID, t.eventID, e.strokeID, e.lengthID, t.time FROM swim_times t, gala_events e WHERE t.member = :member AND e.strokeID = :stroke AND t.eventID = e.id GROUP BY e.lengthID ORDER BY t.time ASC";

        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);
        $stmt->bindParam(':stroke', $stroke, PDO::PARAM_INT);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllPBsForSwimmerByLength($conn, $member, $stroke, $length) {
        $sql = "SELECT t.member, t.galaID, t.eventID, e.strokeID, e.lengthID, t.time FROM swim_times t, gala_events e WHERE t.member = :member AND e.strokeID = :stroke AND e.lengthID = :length AND t.eventID = e.id  ORDER BY t.time ASC LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);
        $stmt->bindParam(':stroke', $stroke, PDO::PARAM_INT);
        $stmt->bindValue(':length', $length, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllPBsForSwimmerByLengthAcc($conn, $member, $stroke, $length, $acc) {

        if ($acc === null) {
            $sql = "SELECT t.member, t.galaID, t.eventID, e.strokeID, e.lengthID, t.time FROM swim_times t, gala_events e, galas g WHERE t.member = :member AND e.strokeID = :stroke AND e.lengthID = :length AND e.galaID = g.id AND g.isAccredited is NULL AND t.eventID = e.id  ORDER BY t.time ASC LIMIT 1";
            $stmt = $conn->prepare($sql);
        } else {
            $sql = "SELECT t.member, t.galaID, t.eventID, e.strokeID, e.lengthID, t.time FROM swim_times t, gala_events e, galas g WHERE t.member = :member AND e.strokeID = :stroke AND e.lengthID = :length AND e.galaID = g.id AND g.isAccredited = :acc AND t.eventID = e.id  ORDER BY t.time ASC LIMIT 1";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':acc', $acc, PDO::PARAM_INT);
        }

        $stmt->bindParam(':member', $member, PDO::PARAM_STR);
        $stmt->bindParam(':stroke', $stroke, PDO::PARAM_INT);
        $stmt->bindValue(':length', $length, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }


    public function isInputValid($gala, $event, $member, $time, $rank) {
        if ($this->isGalaValid($gala) && $this->isEventValid($event) && $this->isMemberValid($member) && $this->isTimeValid($time) && $this->isRankValid($rank)) {
            return true;
        } else {
            return false;
        }
    }

    public function isGalaValid($gala) {
        if (strlen($gala) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isEventValid($event) {
        if (strlen($event) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isMemberValid($member) {
        if (strlen($member) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isTimeValid($time) {
        if (strlen($time) == 8) {
            if (is_numeric(str_replace('.','',str_replace(':','',$time)))) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isRankValid($rank) {
        if (strlen($rank > 0)) {
            if (is_numeric($rank)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

}

?>
