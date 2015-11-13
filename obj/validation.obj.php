<?php

// @author Alan Thom (1103803)
// A class to hold validation functions for all objects
// Make use of DRY principle and be able to call functions from anywhere

require_once "./inc/connection.inc.php";

class Validation {
       
    // ***** ADDRESS FUNCTIONS *****
    public function isAddress1Valid($address1) {
        // DB constraints: varchar(50) NOT NULL
        if (strlen($address1) <= 50 && strlen($address1) > 0) 
            { return true; } 
            else 
            { return false;
        }
    }
    public function isAddress2Valid($address2) {
        // DB constraints: varchar(50) NULL
        if (strlen($address2) <= 50) 
            { return true; } 
            else 
            { return false;
        }
    }
    public function isCityValid($city) {
        // DB constraints: varchar(50) NOT NULL
        if (strlen($city) <= 50 && strlen($city) > 0) 
            { return true; } 
            else 
            { return false;
        }
    }
    public function isCountyValid($county) {
        // DB constraints: varchar(50) NULL
        if (strlen($county) <= 50) 
            { return true; } 
            else 
            { return false;
        }
    }
    /*public function isPostcodeValid($postcode) {
        // DB constraints: varchar(8) NOT NULL
        if (strlen($postcode) == 7 || strlen($postcode) == 8)
            { return true; } 
            else 
            { return false;
        }
    }*/
    public function isTelephoneValid($telephone) {
        // DB constraints: varchar(11) NOT NULL
        if (strlen($telephone) <= 11 && substr($telephone,0,1) == '0') 
            { return true; } 
            else 
            { return false;
        }
    }
    public function isMobileValid($mobile) {
        // DB constraints: varchar(50) NOT NULL
        if (strlen($mobile) <= 11 && substr($mobile,0,2) == '07') 
            { return true; } 
            else 
            { return false;
        }
    }
    /*public function isEmailValid($email) {
        // DB constraints: varchar(250) NULL
        if ($email)
            { return true; } 
            else 
            { return false;
        }
    }*/
    /*public function isWebisteValid($website) {
        // DB constraints: varchar(250) NULL
        if ($website) 
            { return true; } 
            else 
            { return false;
        }
    }*/
    
    // ***** MEMBERS OBJECT VALIDATION *****
    public function isGenderValid($gender) {
        // DB constraints: varchar(8) NOT NULL
        if ($gender == 'M' || $gender == 'F')
            { return true; } 
            else 
            { return false;
        }
    }
    /*public function isDOBValid($dob) {
        // DB constraints: varchar(8) NOT NULL
        if ($dob <= )
            { return true; } 
            else 
            { return false;
        }
    }*/
    /*public function isStatusValid($status) {
        // DB constraints: varchar(8) NOT NULL
        if ($status)
            { return true; } 
            else 
            { return false;
        }
    }*/
    /* public function isSquadIDValid($squadID) {
        // DB constraints: varchar(8) NOT NULL
        if ($squadID)
            { return true; } 
            else 
            { return false;
        }
    } */
    
    // ***** OTHER VALIDATION METHODS *****
}

?>
