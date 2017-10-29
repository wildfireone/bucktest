<?php

// @author Alan Thom (1103803)
// A class to represent a Role object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Members_Roles {

    // ***** PROPERTIES *****    
    private $member, $roleID;
    
    // ***** CONSTRUCTOR *****
    function __construct($member = "zzz", $roleID = -1) {
        $this->member = $member;
        $this->roleID = $roleID;
    }
        
    // ***** GETTERS *****
    public function getMember() {
        return $this->member;
    }
    public function getRoleID() {
        return $this->roleID;
    } 
    
    // ***** SETTERS *****    
    public function setMember($member) {
        $this->member = $member;
    }    
    public function setRoleID($roleID) {
        $this->roleID = $roleID;
    }    
        
    // ***** OTHER METHODS *****    
    public function getAllDetails($conn) {        
        $sql = "SELECT * FROM members_roles WHERE member = :member AND roleID = :roleID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $this->getMember(), PDO::PARAM_STR);
        $stmt->bindParam(':roleID', $this->getRoleID(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            foreach ($results as $row) {
                $this->setMember($row["member"]);
                $this->setRoleID($row["roleID"]); 
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function create($conn) {
        $sql = "INSERT INTO members_roles VALUES (:member, :role)";
            $stmt = $conn->prepare($sql);
        
            $stmt->bindParam(':member', $this->getMember(), PDO::PARAM_STR);
            $stmt->bindParam(':role', $this->getRoleID(), PDO::PARAM_STR);
        
        try {    
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Create failed: " . $e->getMessage();
        }
    }

    public function delete($conn, $member) {
        $sql = "DELETE FROM members_roles WHERE member = :member";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            $array = array();
            foreach ($results as $row) {
                array_push($array,$row["roleID"]);
            }
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }
    
    public function getAllRoles($conn) {        
        $sql = "SELECT r.roleID, r.role FROM members_roles m, roles r WHERE m.roleID = r.id AND member = :member";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $this->getMember(), PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $row) {
                $array[$row["roleID"]] = $array[$row["role"]];
            }
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }

    public function getAllRolesForMember($conn, $member) {        
        $sql = "SELECT roleID FROM members_roles WHERE member = :member";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $row) {
                array_push($array,$row["roleID"]);
            }
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }    
    }
    
    public function listAllSwimmers($conn) {
        $sql = "SELECT m.username, CONCAT(m.firstName, ' ', m.lastName) AS name, s.squad FROM members m, members_roles r, squads s WHERE r.roleID = 18 AND r.member = m.username AND m.squadID = s.id";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                $array[$result["username"]] = $result["name"] . " (" . $result["squad"] . ")";
            }

            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberPresident($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 1 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberSecretary($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 2 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberTreasurer($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 3 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberGalaCoordinator($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 4 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberSTOOfficer($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 5 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberMembershipCoordinator($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 6 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberCPO($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 7 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberBetaLeagueCoordinator($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 8 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberHeadCoach($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 9 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberSwimmerRep($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 10 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberCoachRep($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 11 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberOrdinaryMember($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 12 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberSwimshop($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 13 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberFundraiser($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 14 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    //Administrator
    public function isMemberWebCoordinator($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 15 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberCoach($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 16 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberSTO($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 17 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberSwimmer($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 18 AND member = :member";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function isMemberMeetSecretary($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID = 19 AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }
    
    public function listAllCoaches($conn) {
        $sql = "SELECT m.username, CONCAT(m.firstName, ' ', m.lastName) AS name FROM members m, members_roles r WHERE r.roleID = 16 AND r.member = m.username";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                $array[$result["username"]] = $result["name"];
            }
            
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    public function listCommittee($conn) {
        $sql = "SELECT m.username, r.id FROM members m, members_roles z, roles r WHERE z.roleID = r.id AND r.id IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,19) AND z.member = m.username ORDER BY r.id ASC";
        //$sql = "SELECT m.username, r.id FROM members m, members_roles z, roles r WHERE z.roleID = r.id AND z.member = m.username AND z.roleID IN () ORDER BY r.id DESC";

        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            $array = array();
            foreach ($results as $result) {
                if (array_key_exists($result["id"],$array)) {
                    $list = $array[$result["id"]];
                    array_push($list,$result["username"]);
                    $array[$result["id"]] = $list;
                } else {
                    $list = array();
                    array_push($list,$result["username"]);
                    $array[$result["id"]] = $list;
                }                
            }
            return $array;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }  
    }

    //Checks if member is apart of the Committee.
    public function isMemberCommittee($conn, $member) {
        $sql = "SELECT member FROM members m, members_roles z, roles r WHERE z.roleID = r.id AND r.id IN (1,2,3,4,5,6,7,8,9,10,11,13,14,19) AND z.member = :member";
        //$sql = "SELECT member FROM members_roles WHERE roleID = 13 AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }


    //Security Checks (I.E making my life easier while making the site secure..)


    public function newsFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (1,2,3,6,8,9,12,15,17,19) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function galasFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (4,9,15,19) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function membersFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (2,3,6,9,15,19) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function galleryFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (1,2,3,6,9,15,17,19) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }


    public function shopFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (9,13,15) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function timetableFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (9,15,19) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function squadsFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (9,15,16,19) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }


    public function venuesFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (9,15,16,19) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function pagesFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (9,15) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function filesFullAccess($conn, $member) {
        $sql = "SELECT member FROM members_roles WHERE roleID IN (9,15) AND member = :member";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':member', $member, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }






    public function isInputValid($member, $roleID, $conn) {
        if (isMemberValid($member, $conn) && isRoleValid($roleID, $conn)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isMemberValid($member, $conn) {
        require 'members.obj.php';
        
        $member = new Member($member);
        
        if ($member->doesExist($conn)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isRoleValid($roleID) {
        require 'members.obj.php';
        
        $role = new Roles($roleID);
        
        if ($role->doesExist($conn)) {
            return true;
        } else {
            return false;
        }
    }
}

?>
