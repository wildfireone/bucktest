<?php

// @author Alan Thom (1103803)
// A class to represent a Club Record object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete club records

class Club_Records {

    // ***** PROPERTIES *****    
    private $strokeID, $lengthID, $ageLower, $ageUpper, $femaleMember, $femaleDate, $femaleTime, $maleMember, $maleDate, $maleTime, $eventType;
    
    // ***** CONSTRUCTOR *****
    function __construct($strokeID = -1, $lengthID = -1, $ageLower = -1, $ageUpper = -1, $eventType = -1) {
        $this->strokeID = $strokeID;
        $this->lengthID = $lengthID;
        $this->ageLower = $ageLower;
        $this->ageUpper = $ageUpper;
        $this->eventType = $eventType;
    }
    
    // ***** GETTERS *****
    public function getStrokeID() {
        return $this->strokeID;
    }
    public function getLengthID() {
        return $this->lengthID;
    }    
    public function getAgeLower() {
        return $this->ageLower;
    }    
    public function getAgeUpper() {
        return $this->ageUpper;
    }     
    public function getFemaleMember() {
        return $this->femaleMember;
    }    
    public function getFemaleTime() {
        return $this->femaleTime;
    }    
    public function getMaleMember() {
        return $this->maleMember;
    }
    public function getMaleDate() {
        return $this->maleDate;    
    }
    public function getMaleTime() {
        return $this->maleTime;    
    }       
    public function getEventType() {
        return $this->eventType;
    } 
        
    // ***** SETTERS *****    
    public function setStrokeID($strokeID) {
        $this->strokeID = $strokeID;
    }    
    public function setlengthID($lengthID) {
        $this->lengthID = $lengthID;
    }    
    public function setAgeLower($ageLower) {
        $this->ageLower = $ageLower;
    }    
    public function setAgeUpper($ageUpper) {
        $this->ageUpper = $ageUpper;
    }    
    public function setFemaleMember($femaleMember) {
        $this->femaleMember = $femaleMember;
    }    
    public function setFemaleTime($femaleTime) {
        $this->femaleTime = $femaleTime;
    }
    public function setMaleMember($maleMember) {
        $this->maleMember = $maleMember;
    }
    public function setMaleTime($maleTime) {
        $this->maleTime = $maleTime;
    }
    public function setEventType($eventType) {
        $this->eventType = $eventType;
    }
    
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {        
        $sql = "SELECT * FROM club_records WHERE strokeID = :strokeID AND lengthID = :lengthID AND ageLower = :ageLower AND ageUpper = :ageUpper AND eventType = :eventType";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':strokeID', $this->getStrokeID(), PDO::PARAM_INT);
        $stmt->bindParam(':lengthID', $this->getLengthID(), PDO::PARAM_INT);
        $stmt->bindParam(':ageLower', $this->getAgeLower(), PDO::PARAM_INT);
        $stmt->bindParam(':ageUpper', $this->getAgeUpper(), PDO::PARAM_INT);
        $stmt->bindParam(':eventType', $this->getEventType(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                $this->setStrokeID($row["strokeID"]);
                $this->setLengthID($row["lengthID"]);
                $this->setAgeLower($row["ageLower"]);
                $this->setAgeUpper($row["ageUpper"]);
                $this->setFemaleMember($row["femaleMember"]);
                $this->setFemaleTime($row["femaleTime"]);
                $this->setMaleMember($row["maleMember"]);
                $this->setMaleTime($row["maleTime"]);
                $this->setEventType($row["eventType"]);
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAllClubRecords($conn) {
        $sql = "SELECT * FROM club_records ORDER BY strokeID, lengthID";
        
        $stmt = $conn->prepare($sql);
        
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
