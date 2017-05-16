<?php

// @author Alan Thom (1103803)
// A class to represent a Member object
// Contains all attributes and relevant methods to get/set attributes and create, read, update and delete records

class Members
{

    // ***** PROPERTIES *****    
    private $username, $SASANumber, $status, $firstName, $middleName, $lastName, $gender, $dob, $address1, $address2, $city, $county, $postcode, $telephone, $mobile, $email, $parentTitle, $parentName, $squadID, $registerDate, $lastLoginDate, $monthlyFee, $feeAdjustment, $swimmingHours, $notes, $reset;

    // ***** CONSTRUCTOR *****
    function __construct($username = "zzz")
    {
        $this->username = $username;
    }

    // ***** GETTERS *****
    public function getUsername()
    {
        return $this->username;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getSASANumber()
    {
        return $this->SASANumber;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getDOB()
    {
        return $this->dob;
    }

    public function getAddress1()
    {
        return $this->address1;
    }

    public function getAddress2()
    {
        return $this->address2;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCounty()
    {
        return $this->county;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getParentTitle()
    {
        return $this->parentTitle;
    }

    public function getParentName()
    {
        return $this->parentName;
    }

    public function getSquadID()
    {
        return $this->squadID;
    }

    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }

    public function getMonthlyFee()
    {
        return $this->monthlyFee;
    }

    public function getFeeAdjustment()
    {
        return $this->feeAdjustment;
    }

    public function getSwimmingHours()
    {
        return $this->swimmingHours;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function getReset()
    {
        return $this->reset;
    }

    // ***** SETTERS *****    
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setSASANumber($SASANumber)
    {
        $this->SASANumber = $SASANumber;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function setDOB($dob)
    {
        $this->dob = $dob;
    }

    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function setCounty($county)
    {
        $this->county = $county;
    }

    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setParentTitle($parentTitle)
    {
        $this->parentTitle = $parentTitle;
    }

    public function setParentName($parentName)
    {
        $this->parentName = $parentName;
    }

    public function setSquadID($squadID)
    {
        $this->squadID = $squadID;
    }

    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;
    }

    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;
    }

    public function setMonthlyFee($monthlyFee)
    {
        $this->monthlyFee = $monthlyFee;
    }

    public function setFeeAdjustment($feeAdjustment)
    {
        $this->feeAdjustment = $feeAdjustment;
    }

    public function setSwimmingHours($swimmingHours)
    {
        $this->swimmingHours = $swimmingHours;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function setReset($reset)
    {
        $this->reset = htmlentities($reset);
    }

    // ***** OTHER METHODS *****    
    public function getAllDetails($conn)
    {
        $sql = "SELECT * FROM members WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            foreach ($results as $row) {
                $this->setUsername($row["username"]);
                $this->setSASANumber($row["sasaNumber"]);
                $this->setStatus($row["status"]);
                $this->setFirstName($row["firstName"]);
                $this->setMiddleName($row["middleName"]);
                $this->setLastName($row["lastName"]);
                $this->setGender($row["gender"]);
                $this->setDOB($row["dob"]);
                $this->setAddress1($row["address1"]);
                $this->setAddress2($row["address2"]);
                $this->setCity($row["city"]);
                $this->setCounty($row["county"]);
                $this->setPostcode($row["postcode"]);
                $this->setTelephone($row["telephone"]);
                $this->setMobile($row["mobile"]);
                $this->setEmail($row["email"]);
                $this->setParentTitle($row["parentTitle"]);
                $this->setParentName($row["parentName"]);
                $this->setSquadID($row["squadID"]);
                $this->setRegisterDate($row["registerDate"]);
                $this->setLastLoginDate($row["lastLoginDate"]);
                $this->setMonthlyFee($row["monthlyFee"]);
                $this->setFeeAdjustment($row["feeAdjustment"]);
                $this->setSwimmingHours($row["swimmingHours"]);
                $this->setNotes($row["notes"]);
                $this->setReset($row['reset']);
            }
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function getFullNameByUsername($conn)
    {
        $sql = "SELECT firstName, lastName FROM members WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();

            $name = "";
            foreach ($results as $row) {
                $name = $row["firstName"] . " " . $row["lastName"];
            }
            return $name;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function create($conn, $password)
    {
        if ($this->createMember($conn) && $this->createUser($conn, $password)) {
            return true;
        } else {
            return false;
        }
    }

    public function createMember($conn)
    {
        try {
            //$sql = "INSERT INTO members VALUES (:username, :sasaNumber, :status, :firstName, :middleName, :lastName, :gender, :dob, :address1, :address2, :city, :county, :postcode, :telephone, :mobile, :email, :parentTitle, :parentName, :squadID, :registerDate, :monthlyFee, :feeAdjustment, :swimmingHours, :notes)";
            $sql = "INSERT INTO members VALUES (:sasaNumber, :sasaCategory, :username, :status, :userTypeOld, :firstName, :middleName, :lastName, :gender, :dob, :address1, :address2, :city, :county, :postcode, :telephone, :mobile, :email, :parentTitle, :parentName, :squadID, :registerDate, :lastLoginDate, :monthlyFee, :feeAdjustment, :swimmingHours, :notes, :reset)";


            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':sasaNumber', $this->getSASANumber(), PDO::PARAM_STR);
            $stmt->bindValue(':sasaCategory', null, PDO::PARAM_INT);
            $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);
            $stmt->bindParam(':status', $this->getStatus(), PDO::PARAM_INT);
            $stmt->bindValue(':userTypeOld', null, PDO::PARAM_INT);
            $stmt->bindParam(':firstName', $this->getFirstName(), PDO::PARAM_STR);
            $stmt->bindParam(':middleName', $this->getMiddleName(), PDO::PARAM_STR);
            $stmt->bindParam(':lastName', $this->getLastName(), PDO::PARAM_STR);
            $stmt->bindParam(':gender', $this->getGender(), PDO::PARAM_STR);
            $stmt->bindParam(':dob', $this->getDOB(), PDO::PARAM_STR);
            $stmt->bindParam(':address1', $this->getAddress1(), PDO::PARAM_STR);
            $stmt->bindParam(':address2', $this->getAddress2(), PDO::PARAM_STR);
            $stmt->bindParam(':city', $this->getCity(), PDO::PARAM_STR);
            $stmt->bindParam(':county', $this->getCounty(), PDO::PARAM_STR);
            $stmt->bindParam(':postcode', $this->getPostcode(), PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $this->getTelephone(), PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $this->getMobile(), PDO::PARAM_STR);
            $stmt->bindParam(':email', $this->getEmail(), PDO::PARAM_STR);
            $stmt->bindParam(':parentTitle', $this->getParentTitle(), PDO::PARAM_STR);
            $stmt->bindParam(':parentName', $this->getParentName(), PDO::PARAM_STR);
            $stmt->bindParam(':squadID', $this->getSquadID(), PDO::PARAM_INT);
            $stmt->bindParam(':registerDate', $this->getRegisterDate(), PDO::PARAM_STR);
            $stmt->bindValue(':lastLoginDate', null, PDO::PARAM_INT);
            $stmt->bindParam(':monthlyFee', $this->getMonthlyFee(), PDO::PARAM_STR);
            $stmt->bindParam(':feeAdjustment', $this->getFeeAdjustment(), PDO::PARAM_STR);
            $stmt->bindParam(':swimmingHours', $this->getSwimmingHours(), PDO::PARAM_INT);
            $stmt->bindParam(':notes', $this->getNotes(), PDO::PARAM_STR);
            $stmt->bindParam(':reset', $this->getReset(), PDO::PARAM_INT);

            var_dump($stmt);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            dbClose($conn);
            return "Create failed: " . $e->getMessage();
        } catch (Exception $e) {
            dbClose($conn);
            return "create failed: " . $e->getMessage();
        }
    }

    public function createUser($conn, $password)
    {
        try {
            $sql = "INSERT INTO users VALUES (:username, :password)";

            //First let's hash the password with the bcrypt function
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $hash, PDO::PARAM_STR); //Hello Bcrypt!
            //$stmt->bindParam(':password', md5($password), PDO::PARAM_STR); good bye MD5..

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            dbClose($conn);
            return "Create failed: " . $e->getMessage();
        }
    }

    public function update($conn)
    {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $sql = "UPDATE members SET sasaNumber = :sasaNumber, status = :status, firstName = :firstName, MiddleName = :middleName, lastName = :lastName, gender = :gender, dob = :dob, address1 = :address1, address2 = :address2, city = :city, county = :county, postcode = :postcode, telephone = :telephone, mobile = :mobile, email = :email, parentTitle = :parentTitle, parentName = :parentName, squadID = :squadID, registerDate = :registerDate, monthlyFee = :monthlyFee, feeAdjustment = :feeAdjustment, swimmingHours = :swimmingHours, notes = :notes, reset = :reset WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);
        $stmt->bindParam(':sasaNumber', $this->getSASANumber(), PDO::PARAM_STR);
        $stmt->bindParam(':status', $this->getStatus(), PDO::PARAM_INT);
        $stmt->bindParam(':firstName', $this->getFirstName(), PDO::PARAM_STR);
        $stmt->bindParam(':middleName', $this->getMiddleName(), PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $this->getLastName(), PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $this->getLastName(), PDO::PARAM_STR);
        $stmt->bindParam(':gender', $this->getGender(), PDO::PARAM_STR);
        $stmt->bindParam(':dob', $this->getDOB(), PDO::PARAM_STR);
        $stmt->bindParam(':address1', $this->getAddress1(), PDO::PARAM_STR);
        $stmt->bindParam(':address2', $this->getAddress2(), PDO::PARAM_STR);
        $stmt->bindParam(':city', $this->getCity(), PDO::PARAM_STR);
        $stmt->bindParam(':county', $this->getCounty(), PDO::PARAM_STR);
        $stmt->bindParam(':postcode', $this->getPostcode(), PDO::PARAM_STR);
        $stmt->bindParam(':telephone', $this->getTelephone(), PDO::PARAM_STR);
        $stmt->bindParam(':mobile', $this->getMobile(), PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->getEmail(), PDO::PARAM_STR);
        $stmt->bindParam(':parentTitle', $this->getParentTitle(), PDO::PARAM_STR);
        $stmt->bindParam(':parentName', $this->getParentName(), PDO::PARAM_STR);
        $stmt->bindParam(':squadID', $this->getSquadID(), PDO::PARAM_INT);
        $stmt->bindParam(':registerDate', $this->getRegisterDate(), PDO::PARAM_STR);
        $stmt->bindParam(':monthlyFee', $this->getMonthlyFee(), PDO::PARAM_STR);
        $stmt->bindParam(':feeAdjustment', $this->getFeeAdjustment(), PDO::PARAM_STR);
        $stmt->bindParam(':swimmingHours', $this->getSwimmingHours(), PDO::PARAM_INT);
        $stmt->bindParam(':notes', $this->getNotes(), PDO::PARAM_STR);
        $stmt->bindParam(':reset', $this->getReset(), PDO::PARAM_INT);

        try {
            $stmt->execute();
            dbClose($conn);
            return true;
        } catch (PDOException $e) {
            dbClose($conn);
            return "Update failed: " . $e->getMessage();
        } catch (Exception $e) {
            dbClose($conn);
            return "Update failed: " . $e->getMessage();
        }

    }

    public function updatePassword($conn, $password)
    {
        try {
            $sql = "UPDATE users SET password = :password WHERE username = :username";

            //First let's hash the password with the bcrypt function
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);
            $stmt->bindValue(':password', $hash, PDO::PARAM_STR);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            dbClose($conn);
            return "Create user password failed: " . $e->getMessage();
        }
    }

    public function checkPassword($pwd, &$errors) {
        $errors_init = $errors;

        if (strlen($pwd) < 8) {
            $errors[] = "Password too short!";
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors[] = "Password must include at least one number!";
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors[] = "Password must include at least one letter!";
        }

        return ($errors == $errors_init);
    }

    public function checkPasswordReset($conn,$pwd, &$errors) {
        $errors_init = $errors;
        $samePassword = "";

        try {
            $sql = "SELECT username FROM users WHERE username = :username AND password = :password";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', htmlentities($this->getUsername()), PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll();
            $hash = @$results[0]['password'];

            if (isset($results)) {
                if (password_verify($pwd, $hash)) {
                    $samePassword = true;
                } else {
                    $samePassword = false;
                }
            } else {
                $samePassword = false;
            }
        } catch (PDOException $e) {
            return "Login failed: " . $e->getMessage();
        }


        if($samePassword)
        {
            $errors[] = "Password is the same as the current one, please try another!";
        }

        if (strlen($pwd) < 8) {
            $errors[] = "Password too short!";
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors[] = "Password must include at least one number!";
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors[] = "Password must include at least one letter!";
        }

        return ($errors == $errors_init);
    }

    public function updateResetFlag($conn)
    {
        try {
            $sql = "UPDATE members SET reset = :reset WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':reset', $this->getReset(), PDO::PARAM_INT);
            $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            dbClose($conn);
            return "Create failed: " . $e->getMessage();
        }

    }


    //Delete Members

    public function delete($conn)
    {
        if ($this->deleteMember($conn) && $this->deleteUser($conn)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($conn)
    {
        try {
            $sql = "DELETE FROM users WHERE username = :username";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);

            echo "Delete user complete";
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Delete user failed: " . $e->getMessage();
        }
    }

    public function deleteMember($conn)
    {
        try {
            $sql = "DELETE FROM members WHERE username = :username";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Delete member failed: " . $e->getMessage();
        }
    }


    public function listAllMembers($conn, $name = null)
    {
        $sql = "SELECT m.username, s.squad FROM members m LEFT JOIN squads s ON m.squadid = s.id";

        if (!is_null($name)) {
            $sql .= " WHERE m.firstName = :name OR m.lastName = :name";
        }

        $stmt = $conn->prepare($sql);

        if (!is_null($name)) {
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        }

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    //List all current members of the club (status = 1)
    public function listAllCurrentMembers($conn, $name = null)
    {
        $sql = "SELECT m.username, s.squad FROM members m LEFT JOIN squads s ON m.squadid = s.id WHERE m.status = 1";

        if (!is_null($name)) {
            $sql .= " WHERE m.firstName = :name OR m.lastName = :name";
        }

        $stmt = $conn->prepare($sql);

        if (!is_null($name)) {
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        }

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }

    //List all members based on search results
    public function listMemberSearchResults($conn, $status = null, $squadID = null, $role = null, $name = null)
    {
        //parameter counter
        $count = 0;

        if (is_null($role)) {
            $sql = "SELECT m.username, s.squad  FROM members m LEFT JOIN squads s ON m.squadid = s.id";
            $count = 0;
        }

        if (!is_null($role)) {
            $sql = "SELECT m.username, s.squad  FROM members m LEFT JOIN squads s ON m.squadid = s.id LEFT JOIN members_roles r ON r.member = m.username WHERE r.roleID = :role";
            $sql .= "";
            $count++;
        }


        //If parameters are not null then add them to the SQL query
        if (!is_null($status)) {
            if ($count == 0) {
                $sql .= " WHERE m.status = :status";
            } else {
                $sql .= " AND m.status = :status";
            }
            $count++;
        }

        if (!is_null($squadID)) {
            if ($count == 0) {
                $sql .= " WHERE m.squadid = :squadID";
            } else {
                $sql .= " AND m.squadid = :squadID";
            }
            $count++;
        }


        if (!is_null($name)) {
            if ($count == 0) {
                $sql .= " WHERE m.firstName = :name OR m.lastName = :name";
            } else {
                $sql .= " AND m.firstName = :name OR m.lastName = :name";

            }
            $count++;
        }

        $stmt = $conn->prepare($sql);

        //Also bind them to them a keyword:

        if (!is_null($status)) {
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        }

        if (!is_null($squadID)) {
            $stmt->bindParam(':squadID', $squadID, PDO::PARAM_STR);
        }

        if (!is_null($role)) {
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        }

        if (!is_null($name)) {
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        }

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }


    //List all members which are in a squad (via url parameter)
    public function listAllSquadMembers($conn, $squadID, $name = null)
    {
        $sql = "SELECT m.username, s.squad FROM members m  LEFT JOIN squads s ON m.squadid = s.id WHERE m.squadid = '$squadID'";

        if (!is_null($name)) {
            $sql .= " WHERE m.firstName = :name OR m.lastName = :name";
        }

        $stmt = $conn->prepare($sql);

        if (!is_null($name)) {
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        }

        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (PDOException $e) {
            return "Database query failed: " . $e->getMessage();
        }
    }


    public function listGenders($mixed = false)
    {
        if (!$mixed) {
            return array("M" => "Male", "F" => "Female");
        } else {
            return array("M" => "Male", "F" => "Female", "A" => "Mixed");
        }
    }

    public function listParentTitles()
    {
        return array("Mr" => "Mr", "Mrs" => "Mrs", "Ms" => "Ms", "MrMrs" => "MrMrs");
    }

    public function doesExist($conn)
    {
        $sql = "SELECT username FROM members WHERE username = :username LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);
        try {
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (count($results) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    function updateLastLogin($conn)
    {

        $sql = "UPDATE members SET lastLoginDate = NOW() WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $this->getUsername(), PDO::PARAM_STR);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }

    }

    public function isInputValid($conn, $username, $sasaNumber, $status, $firstName, $middleName, $lastName, $gender, $dob, $address1, $address2, $city, $county, $postcode, $telephone, $mobile, $email, $parentTitle, $parentName, $squadID, $registerDate, $lastLoginDate, $monthlyFee, $feeAdjustment, $swimmingHours, $notes)
    {
        if ($this->isUsernameValid($conn, $username) &&
            $this->isSASANumberValid($sasaNumber) &&
            $this->isStatusValid($status) &&
            $this->isFirstNameValid($firstName) &&
            $this->isMiddleNameValid($middleName) &&
            $this->isLastNameValid($lastName) &&
            $this->isGenderValid($gender) &&
            $this->isDOBValid($dob) &&
            $this->isAddress1Valid($address1) &&
            $this->isAddress2Valid($address2) &&
            $this->isCityValid($city) &&
            $this->isCountyValid($county) &&
            $this->isPostcodeValid($postcode) &&
            $this->isTelephoneValid($telephone) &&
            $this->isMobileValid($mobile) &&
            $this->isEmailValid($email) &&
            $this->isParentTitleValid($parentTitle) &&
            $this->isParentNameValid($parentName) &&
            $this->isSquadValid($squadID) &&
            $this->isRegisterDateValid($registerDate) &&
            $this->isLastLoginDateValid($lastLoginDate) &&
            $this->isMonthlyFeeValid($monthlyFee) &&
            $this->isFeeAdjustmentValid($feeAdjustment) &&
            $this->isSwimmingHoursValid($swimmingHours) &&
            $this->isNotesValid($notes)
        ) {
            return true;
        } else {
            return false;
            print_r('false');
        }
    }

    public function isInputValidEdit($sasaNumber, $status, $firstName, $middleName, $lastName, $gender, $dob, $address1, $address2, $city, $county, $postcode, $telephone, $mobile, $email, $parentTitle, $parentName, $squadID, $registerDate, $monthlyFee, $feeAdjustment, $swimmingHours, $notes)
    {
        if ($this->isSASANumberValid($sasaNumber) &&
            $this->isStatusValid($status) &&
            $this->isFirstNameValid($firstName) &&
            $this->isMiddleNameValid($middleName) &&
            $this->isLastNameValid($lastName) &&
            $this->isGenderValid($gender) &&
            $this->isDOBValid($dob) &&
            $this->isAddress1Valid($address1) &&
            $this->isAddress2Valid($address2) &&
            $this->isCityValid($city) &&
            $this->isCountyValid($county) &&
            $this->isPostcodeValid($postcode) &&
            $this->isTelephoneValid($telephone) &&
            $this->isMobileValid($mobile) &&
            $this->isEmailValid($email) &&
            $this->isParentTitleValid($parentTitle) &&
            $this->isParentNameValid($parentName) &&
            $this->isSquadValid($squadID) &&
            $this->isRegisterDateValid($registerDate) &&
            $this->isMonthlyFeeValid($monthlyFee) &&
            $this->isFeeAdjustmentValid($feeAdjustment) &&
            $this->isSwimmingHoursValid($swimmingHours) &&
            $this->isNotesValid($notes)
        ) {
            return true;
        } else {
            return false;
            print_r('false');
        }
    }

    public function isFirstNameValid($firstName)
    {
        if (count($firstName > 0) && count($firstName <= 50)) {
            return true;
        } else {
            return false;
        }
    }

    public function isMiddleNameValid($middleName)
    {
        if (count($middleName <= 50)) {
            return true;
        } else {
            return false;
        }
    }

    public function isLastNameValid($lastName)
    {
        if (count($lastName > 0) && count($lastName <= 50)) {
            return true;
        } else {
            return false;
        }
    }

    public function isUsernameValid($conn, $username)
    {
        $sql = "SELECT username FROM members WHERE username = :username";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        try {
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                return true;
                echo 'true';
            } else {
                return false;
                echo 'false';
            }
        } catch (PDOException $e) {
            return "Query failed: " . $e->getMessage();
        }
    }

    public function isDOBValid($dob)
    {
        if (strtotime($dob) < time()) {
            return true;
        } else {
            return false;
        }
    }

    public function isGenderValid($gender)
    {
        if ($gender == 'M' || $gender == 'F') {
            return true;
        } else {
            return false;
        }
    }

    public function isStatusValid($status)
    {
        if (is_numeric($status)) {
            if ($status == 1 || $status == 2 || $status == 3) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isSASANumberValid($sasaNumber)
    {
        if (count($sasaNumber) <= 15) {
            return true;
        } else {
            return false;
        }
    }

    public function isRegisterDateValid($registerDate)
    {
        if (strtotime($registerDate)) {
            return true;
        } else {
            return false;
        }
    }

    public function isLastLoginDateValid($lastLoginDate)
    {
        if (count($lastLoginDate) > 0) {
            if (strtotime($lastLoginDate)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isParentTitleValid($parentTitle)
    {
        if ($parentTitle == 'Mr' || $parentTitle == 'Mrs' || $parentTitle == 'Ms') {
            return true;
        } else {
            return false;
        }
    }

    public function isParentNameValid($parentName)
    {
        if (count($parentName <= 100)) {
            return true;
        } else {
            return false;
        }
    }

    public function isAddress1Valid($address1)
    {
        if (count($address1) > 0 && count($address1) <= 50) {
            return true;
        } else {
            return false;
        }
    }

    public function isAddress2Valid($address2)
    {
        if (count($address2) <= 50) {
            return true;
        } else {
            return false;
        }
    }

    public function isCityValid($city)
    {
        if (count($city) > 0 && count($city) <= 50) {
            return true;
        } else {
            return false;
        }
    }

    public function isCountyValid($county)
    {
        if (count($county) <= 50) {
            return true;
        } else {
            return false;
        }
    }

    public function isPostcodeValid($postcode)
    {
        return $this->checkPostcode($postcode);
    }

    public function isTelephoneValid($telephone)
    {
        if (strlen($telephone) > 0 && strlen($telephone) <= 12) {
            if (is_numeric($telephone)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isMobileValid($mobile)
    {
        if (strlen($mobile) > 0 && strlen($mobile) <= 12) {
            if (is_numeric($mobile)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isEmailValid($email)
    {
        if (strlen($email) > 0 && strlen($email) <= 250) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isSquadValid($squad)
    {
        if (!is_null($squad)) {
            return true;
        } else {
            return false;
        }
    }

    public function isSwimmingHoursValid($hours)
    {
        if (strlen($hours) > 0) {
            if (is_numeric($hours)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isMonthlyFeeValid($fees)
    {
        if (strlen($fees) > 0) {
            if (is_numeric($fees)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isFeeAdjustmentValid($fees)
    {
        if (strlen($fees) > 0) {
            if (is_numeric($fees)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function isNotesValid($notes)
    {
        if (count($notes <= 2500)) {
            return true;
        } else {
            return false;
        }
    }


    /*==============================================================================
    Application:   Utility Function
    Author:        John Gardner

    Version:       V1.0
    Date:          25th December 2004
    Description:   Used to check the validity of a UK postcode

    Version:       V2.0
    Date:          8th March 2005
    Description:   BFPO postcodes implemented.
                   The rules concerning which alphabetic characters are alllowed in
                   which part of the postcode were more stringently implementd.

    Version:       V3.0
    Date:          8th August 2005
    Description:   Support for Overseas Territories added

    Version:       V3.1
    Date:          23rd March 2008
    Description:   Problem corrected whereby valid postcode not returned, and
                                 'BD23 DX' was invalidly treated as 'BD2 3DX' (thanks Peter
                   Graves)

    Version:       V4.0
    Date:          7th October 2009
    Description:   Character 3 extended to allow 'pmnrvxy' (thanks to Jaco de Groot)

    Version:       V4.1
    Date:          8th Septemeber 2011
    Description:   ereg and ereg_replace replaced with preg_match and preg_replace
                   BFPO support improved
                   Add check for Anquilla

    Version:       V5.0
    Date:          8th November 2012
                   Specific support added for new BFPO postcodes

    Parameters:    $postcode - postcode to be checked. This is returned reformatted
                   if valid.

    This function checks the value of the parameter for a valid postcode format. The
    space between the inward part and the outward part is optional, although is
    inserted if not there as it is part of the official postcode.

    The functions returns a value of false if the postcode is in an invalid format,
    and a value of true if it is in a valid format. If the postcode is valid, the
    parameter is loaded up with the postcode in capitals, and a space between the
    outward and the inward code to conform to the correct format.

    Example call:

        if (!checkPostcode ($postcode) ) {
          echo 'Invalid postcode <br>';
        }

    ------------------------------------------------------------------------------*/
    public function checkPostcode(&$toCheck)
    {

        // Permitted letters depend upon their position in the postcode.
        $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
        $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
        $alpha3 = "[abcdefghjkpmnrstuvwxy]";                            // Character 3
        $alpha4 = "[abehmnprvwxy]";                                     // Character 4
        $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5
        $BFPOa5 = "[abdefghjlnpqrst]{1}";                               // BFPO character 5
        $BFPOa6 = "[abdefghjlnpqrstuwzyz]{1}";                          // BFPO character 6

        // Expression for BF1 type postcodes
        $pcexp[0] = '/^(bf1)([[:space:]]{0,})([0-9]{1}' . $BFPOa5 . $BFPOa6 . ')$/';

        // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
        $pcexp[1] = '/^(' . $alpha1 . '{1}' . $alpha2 . '{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Expression for postcodes: ANA NAA
        $pcexp[2] = '/^(' . $alpha1 . '{1}[0-9]{1}' . $alpha3 . '{1})([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Expression for postcodes: AANA NAA
        $pcexp[3] = '/^(' . $alpha1 . '{1}' . $alpha2 . '{1}[0-9]{1}' . $alpha4 . ')([[:space:]]{0,})([0-9]{1}' . $alpha5 . '{2})$/';

        // Exception for the special postcode GIR 0AA
        $pcexp[4] = '/^(gir)([[:space:]]{0,})(0aa)$/';

        // Standard BFPO numbers
        $pcexp[5] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';

        // c/o BFPO numbers
        $pcexp[6] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';

        // Overseas Territories
        $pcexp[7] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';

        // Anquilla
        $pcexp[8] = '/^ai-2640$/';

        // Load up the string to check, converting into lowercase
        $postcode = strtolower($toCheck);

        // Assume we are not going to find a valid postcode
        $valid = false;

        // Check the string against the six types of postcodes
        foreach ($pcexp as $regexp) {

            if (preg_match($regexp, $postcode, $matches)) {

                // Load new postcode back into the form element
                $postcode = strtoupper($matches[1] . ' ' . $matches [3]);

                // Take account of the special BFPO c/o format
                $postcode = preg_replace('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

                // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
                if (preg_match($pcexp[7], strtolower($toCheck), $matches)) $postcode = 'AI-2640';

                // Remember that we have found that the code is valid and break from loop
                $valid = true;
                break;
            }
        }

        // Return with the reformatted valid postcode in uppercase if the postcode was
        // valid
        if ($valid) {
            $toCheck = $postcode;
            return true;
        } else return false;
    }

}

?>
