<?php

// @author Alan Thom (1103803)
// A class to represent a Gala object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class GalaEvents {

    // ***** PROPERTIES *****    
    protected $id, $galaID;
    private $strokeID, $lengthID, $gender, $ageLower, $ageUpper, $subGroup;
    
    // ***** CONSTRUCTOR *****
    function __construct($id = 'zzz', $galaID = 'zzz') {
        $this->id = $id;
        $this->galaID = $galaID;
    }
        
    // ***** GETTERS *****
    public function getID() {
        return $this->id;
    }
    public function getGalaID() {
        return $this->galaID;
    }
    public function getStrokeID() {
        return $this->strokeID;
    }
    public function getLengthID() {
        return $this->lengthID;
    }
    public function getGender() {
        return $this->gender;
    }
    public function getAgeLower() {
        return $this->ageLower;
    }
    public function getAgeUpper() {
        return $this->ageUpper;
    }
    public function getSubGroup() {
        return $this->subGroup;
    }
    
    // ***** SETTERS *****    
    public function setID($id) {
        $this->id = $id;
    }
    public function setGalaID($galaID) {
        $this->galaID = $galaID;
    }
    public function setStrokeID($strokeID) {
        $this->strokeID = $strokeID;
    }
    public function setLengthID($lengthID) {
        $this->lengthID = $lengthID;
    }
    public function setGender($gender) {
        $this->gender = $gender;
    }
    public function setAgeLower($ageLower) {
        $this->ageLower = $ageLower;
    }
    public function setAgeUpper($ageUpper) {
        $this->ageUpper = $ageUpper;
    }
    public function setSubGroup($subGroup) {
        $this->subGroup = $subGroup;
    }
    
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn, $galaID) {
        $sql = "SELECT * FROM gala_events WHERE id = '". $this->getID() ."' AND galaID = '". $galaID ."'";
        $stmt = $conn->prepare($sql);
        //$stmt->bindParam(':id', $this->getID(), PDO::PARAM_STR);
        //$stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                //$this->setID($row["id"]);
                //$this->setGalaID($row["galaID"]);
                $this->setStrokeID($row["strokeID"]);
                $this->setLengthID($row["lengthID"]);
                $this->setGender($row["gender"]);
                $this->setAgeLower($row["ageLower"]);
                $this->setAgeUpper($row["ageUpper"]);
                $this->setSubGroup($row["subGroup"]);
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }  

    public function create($conn) {
        $sql = "INSERT INTO gala_events VALUES (:id, :galaID, :stroke, :length, :gender, :ageLower, :ageUpper, :subGroup)";
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_STR);
        $stmt->bindParam(':galaID', $this->getGalaID(), PDO::PARAM_STR);
        $stmt->bindParam(':stroke', $this->getStrokeID(), PDO::PARAM_INT);
        $stmt->bindParam(':length', $this->getLengthID(), PDO::PARAM_INT);
        $stmt->bindParam(':gender', $this->getGender(), PDO::PARAM_STR);
        $stmt->bindParam(':ageLower', $this->getAgeLower(), PDO::PARAM_INT);
        $stmt->bindParam(':ageUpper', $this->getAgeUpper(), PDO::PARAM_INT);
        $stmt->bindParam(':subGroup', $this->getSubGroup(), PDO::PARAM_STR);

        try {
            $stmt->execute(); 
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }    
    }    

    public function update($conn) {
        $sql = "UPDATE gala_events SET strokeID = :stroke, lengthID = :length, gender = :gender, ageLower = :ageLower, ageUpper = :ageUpper, subGroup = :subGroup WHERE id = :id AND galaID = :galaID";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_STR);
        $stmt->bindParam(':galaID', $this->getGalaID(), PDO::PARAM_STR);
        $stmt->bindParam(':stroke', $this->getStrokeID(), PDO::PARAM_INT);
        $stmt->bindParam(':length', $this->getLengthID(), PDO::PARAM_INT);
        $stmt->bindParam(':gender', $this->getGender(), PDO::PARAM_STR);
        $stmt->bindParam(':ageLower', $this->getAgeLower(), PDO::PARAM_INT);
        $stmt->bindParam(':ageUpper', $this->getAgeUpper(), PDO::PARAM_INT);
        $stmt->bindParam(':subGroup', $this->getSubGroup(), PDO::PARAM_STR);

        try {
            $stmt->execute(); 
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }    
    } 

    public function delete($conn) {
        $sql = "DELETE FROM gala_events WHERE id = :id AND galaID = :galaID";
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_STR);
        $stmt->bindParam(':galaID', $this->getGalaID(), PDO::PARAM_STR);

        try {
            $stmt->execute(); 
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }    
    } 

    public function doesExist($conn) {
        $sql = "SELECT * FROM gala_events WHERE id = :id AND galaID = :galaID LIMIT 1";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':id', $this->getID(), PDO::PARAM_STR);
        $stmt->bindParam(':galaID', $this->getGalaID(), PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }
    
    public function listAllGalaEvents($conn, $galaID) {       
        $sql = "SELECT id FROM gala_events WHERE galaID = :galaID ORDER BY strokeID ASC, lengthID ASC, ageLower ASC, gender ASC";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllEvents($conn, $galaID) {       
        $sql = "SELECT e.id, s.stroke, l.length, e.gender, e.ageLower, e.ageUpper FROM gala_events e, strokes s, lengths l WHERE e.galaID = :galaID AND e.strokeID = s.id AND e.lengthID = l.id ORDER BY strokeID ASC, lengthID ASC, ageLower ASC, gender ASC";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            $array = array();
            foreach ($results as $result) {
                $array[$result["id"]] = $result["stroke"] . " (" . $result["length"] . ", " . $result["gender"] . ", " . $result["ageLower"] . "-" . $result["ageUpper"] . ")";
            }
            return $array;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllGalaEventsByStroke($conn, $galaID, $stroke) {       
        $sql = "SELECT id FROM gala_events WHERE galaID = '".$galaID."' AND strokeID = '".$stroke."' ORDER BY strokeID ASC, lengthID ASC, ageLower ASC, gender ASC";
        
        $stmt = $conn->prepare($sql); 
        //$stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        //$stmt->bindParam(':stroke', $stroke, PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    public function listAllGalaEventsForMemberAndStroke($conn, $galaID, $member, $stroke) {       
        $sql = "SELECT DISTINCT s.eventID FROM swim_times s, gala_events e WHERE s.eventID = e.id AND s.galaID = :galaID AND s.member = :member AND e.strokeID = :stroke AND s.time != '59:59:59' AND s.time != '00:00:00' ORDER BY e.lengthID ASC, e.ageLower ASC, e.ageUpper ASC";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
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


    public function listStrokesForGala($conn, $galaID) {       
        $sql = "SELECT DISTINCT strokeID FROM gala_events WHERE galaID = '". $galaID ."' ORDER BY strokeID ASC";
        
        $stmt = $conn->prepare($sql); 
        //$stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    //List all ranks for swim times in array
    public function listRanks()
    {
        $ranks = array(
            '' => "No Rank",
            -1  => "Speeding Ticket",
            1  => "1",
            2  => "2",
            3  => "3",
            4  => "4",
            5  => "5",
            6  => "6",
            7  => "7",
            8  => "8",
            98  => "No Show",
            99  => "DQ",
        );
       return $ranks;
    }



                
    public function doResultsExist($conn, $galaID) {
        $sql = "SELECT member FROM swim_times WHERE galaID = :galaID AND eventID = :eventID LIMIT 1";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        $stmt->bindParam(':eventID', $this->getID(), PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (count($results) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }
                
    public function numberOfResults($conn, $galaID) {
        $sql = "SELECT member FROM swim_times WHERE galaID = :galaID AND eventID = :eventID";
        
        $stmt = $conn->prepare($sql); 
        $stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
        $stmt->bindParam(':eventID', $this->getID(), PDO::PARAM_STR);
        
         try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return count($results);
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }   

    public function isInputValid($id='zzz', $galaID, $strokeID, $lengthID, $gender, $ageLower, $ageUpper, $subGroup, $conn) {
        if ($this->isIDValid($id, $galaID, $conn) && $this->isGalaIDValid($galaID,$conn) && $this->isStrokeIDValid($strokeID,$conn) && $this->isLengthIDValid($lengthID,$conn) && $this->isGenderValid($gender) && $this->isAgeLowerValid($ageLower) && $this->isAgeUpperValid($ageUpper) && $this->isSubGroupValid($subGroup)) {
            return true;
        } else {
            return false;
        }
    }         

    public function isIDValid($id, $galaID, $conn) {
        if ($id = 'zzz') {
            return true;
        } else {
            if (count($id) > 0) {

                $sql = "SELECT id FROM gala_events WHERE galaID = :galaID";
                
                $stmt = $conn->prepare($sql); 
                $stmt->bindParam(':galaID', $galaID, PDO::PARAM_STR);
                
                 try {
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    if ($stmt->rowCount() > 0) {
                        return false;
                    } else {
                        return true;
                    }
                } catch (PDOException $e) {
                    return "Database query failed: " . $e->getMessage();
                }
            } else {
                return false;
            }
        }
    }

    public function isGalaIDValid($galaID,$conn) {
        if (count($galaID) > 0) {

            $sql = "SELECT id FROM galas WHERE id = :id";
            
            $stmt = $conn->prepare($sql); 
            $stmt->bindParam(':id', $galaID, PDO::PARAM_STR);
            
             try {
                $stmt->execute();
                $results = $stmt->fetchAll();
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                return "Database query failed: " . $e->getMessage();
            }
        } else {
            return false;
        }
    }

    public function isStrokeIDValid($strokeID,$conn) {
        if (count($strokeID) > 0) {

            $sql = "SELECT id FROM strokes WHERE id = :id";
            
            $stmt = $conn->prepare($sql); 
            $stmt->bindParam(':id', $strokeID, PDO::PARAM_STR);
            
             try {
                $stmt->execute();
                $results = $stmt->fetchAll();
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                return "Database query failed: " . $e->getMessage();
            }
        } else {
            return false;
        }
    }

    public function isLengthIDValid($lengthID,$conn) {
        if (count($lengthID) > 0) {

            $sql = "SELECT id FROM lengths WHERE id = :id";
            
            $stmt = $conn->prepare($sql); 
            $stmt->bindParam(':id', $lengthID, PDO::PARAM_STR);
            
             try {
                $stmt->execute();
                $results = $stmt->fetchAll();
                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                return "Database query failed: " . $e->getMessage();
            }
        } else {
            return false;
        }
    }

    public function isGenderValid($gender) {
        if (count($gender) > 0) {
            if ($gender == 'M' || $gender == 'F' || $gender == 'A') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isAgeLowerValid($ageLower) {
        if (strlen($ageLower <> 0)) {
            if (is_numeric($ageLower)) {
                if ($ageLower >= 8 && $ageLower <=15) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isAgeUpperValid($ageUpper) {
        if (strlen($ageUpper <> 0)) {
            if (is_numeric($ageUpper)) {
                if ($ageUpper >= 8 && $ageUpper <=15) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isSubGroupValid($subGroup) {
        if (strlen($subGroup <= 1)) {
            return true;
        } else {
            return false;
        }
    }
                
}