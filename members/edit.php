<?php 
    session_start(); 

    require '../inc/connection.inc.php';
    require '../obj/members.obj.php';
    require '../obj/members_roles.obj.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    // Check for a parameter before we send the header
    if (is_null($_GET["u"])) {        
        header( 'Location:' . $domain . '404.php' );
        exit;
    } else {
        $connection = dbConnect();
        $members = new Members($_GET["u"]);
        if (!$members->doesExist($connection)) {
            header( 'Location:' . $domain . '404.php' );
            exit;
        }
    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $memberValidation = new Members($_GET["u"]);
        
        if ($memberValidation->isInputValid($connection, $_POST['txtFirstName'], $_POST['txtMiddleName'], $_POST['txtLastName'], $_POST['txtUsername'], $_POST['txtDOB'],$_POST['sltGender'],$_POST['sltStatus'],$_POST['txtSASANumber'],$_POST['txtRegisterDate'],$_POST['txtParentTitle'],$_POST['txtParentName'],$_POST['txtAddress1'],$_POST['txtAddress2'],$_POST['txtCity'],$_POST['txtCounty'],$_POST['txtPostcode'],$_POST['txtTelephone'],$_POST['txtMobile'],$_POST['txtEmail'], $_POST['txtHours'], $_POST['txtFees']) && count($_POST['chkRoles']) > 0) {
            $memberValidation->setFirstName($_POST['txtFirstName']);
            
            $memberValidation->update($connection);
            $_SESSION['update'] = true;
            
            header('Location:' .$domain . '/members/view.php?u=' . $memberValidation->getUsername());
            die();
        } else {
            $_SESSION['invalid'] = true;
        }
        dbClose($connection);
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Edit | Members | Bucksburn Amatuer Swimming Club</title>    
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>   
    <link href="../css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include '../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="../index.php" role="link">Home</a></li>
                <li><a href="../members.php" role="link">Members</a></li>
                <li class="current">Edit a Member</li>
            </ul>

            <h2>Edit a Member</h2>
           
                <?php
                require '../inc/forms.inc.php';
                require '../obj/status.obj.php';
                require '../obj/roles.obj.php';
                require '../obj/squads.obj.php';

                $conn = dbConnect();

                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error updating the member. Please try again.</p>';
                    unset($_SESSION['error']);
                }
                
                $status = New Status();
                $roles = New Roles();
                $squads = New Squads();
                $members_roles = New Members_Roles();

                $member = new Members($_GET["u"]);
                $member->getAllDetails($conn);
    
                echo formStart();

                echo '<div class="large-6 medium-6 small-12 left"><fieldset><legend>Personal Details</legend>';
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtFirstName"])) {
                        echo textInputEmptyError(true, "First Name", "txtFirstName", "errEmptyFirstName", "Please enter a First Name", 50);
                    } else {
                        echo textInputPostback(true,"First Name","txtFirstName", $_POST["txtFirstName"], 50);
                    }
                } else {
                    echo textInputSetup(true,"First Name","txtFirstName",$member->getFirstName(),50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"Middle Name","txtMiddleName", $_POST["txtMiddleName"], 50);
                } else {
                    echo textInputSetup(false,"Middle Name","txtMiddleName",$member->getMiddleName(),50);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtLastName"])) {
                        echo textInputEmptyError(true, "Last Name", "txtLastName", "errEmptyLastName", "Please enter a Last Name", 50);
                    } else {
                        echo textInputPostback(true,"Last Name","txtLastName", $_POST["txtLastName"], 50);
                    }
                } else {
                    echo textInputSetup(true,"Last Name","txtLastName",$member->getLastName(),50);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtUsername"])) {
                        echo textInputEmptyError(true, "Username", "txtUsername", "errEmptyUsername", "Please enter a Username", 8);
                    } else {
                        if ($member->isUsernameValid($conn, $_POST['txtUsername'])) {
                            echo textInputPostback(true,"Username","txtUsername", $_POST["txtUsername"], 8);
                        } else {
                            echo textInputPostbackError(true, "Username", "txtUsername", $_POST['txtUsername'], "errErrUsername", "This username is already taken. Please enter a unique username", 8);
                        }                        
                    }
                } else {
                    echo textInputSetup(true,"Username","txtUsername",$member->getUsername(),8);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo passwordInputEmptyError(true, "Password", "txtPassword", "errEmptyPassword", "Please enter a Password", 16);         
                } else {
                    echo passwordInputBlank(true,"Password","txtPassword",16);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtDOB"])) {
                        echo dateInputEmptyError(true, "Date of Birth", "txtDOB", "errEmptyDOB", "Please enter a Date of Birth", null, date("Y-m-d"));
                    } else {
                        echo dateInputPostback(true,"Date of Birth", "txtDOB", $_POST["txtDOB"], null, date("Y-m-d"));
                    }
                } else {
                    echo dateInputSetup(true, "Date of Birth", "txtDOB", $member->getDOB(), null, date("Y-m-d"));
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltGender"])) {
                        echo comboInputEmptyError(true,"Gender","sltGender","Please select...", "errEmptyGender", "Please select a Gender", $member->listGenders());
                    } else {
                        echo comboInputPostback(true, "Gender", "sltGender", $_POST["sltGender"], $member->listGenders());
                    }
                } else {
                    echo comboInputSetup(true,"Gender","sltGender",$member->getGender(), $member->listGenders());
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltStatus"])) {
                        echo comboInputEmptyError(true,"Member Status","sltStatus","Please select...", "errEmptyStatus", "Please select a Status", $status->listAllStatus($conn));
                    } else {
                        echo comboInputPostback(true, "Member Status", "sltStatus", $_POST["sltStatus"], $status->listAllStatus($conn));
                    }
                } else {
                    echo comboInputSetup(true,"Member Status","sltStatus",$member->getStatus(), $status->listAllStatus($conn));
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtRegisterDate"])) {
                        echo dateInputEmptyError(true, "Date Joined", "txtRegisterDate", "errEmptyRegisterDate", "Please enter a Date Joined", null,null);
                    } else {
                        echo dateInputPostback(true,"Date Joined", "txtRegisterDate", $_POST["txtRegisterDate"], null, null);
                    }
                } else {
                    echo dateInputSetup(true, "Date Joined", "txtRegisterDate", $member->getRegisterDate(), null, null);
                }
                
                echo '</fieldset></div><div class="large-6 medium-6 small-12 right"><fieldset><legend>Contact Details</legend>';

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"Parent Title</b> (Mr, Mrs or Ms)","txtParentTitle",$_POST["txtParentTitle"],4);
                } else {
                    echo textInputSetup(false,"Parent Title</b> (Mr, Mrs or Ms)","txtParentTitle",$member->getParentTitle(),4);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"Parent Name","txtParentName",$_POST["txtParentName"],100);
                } else {
                    echo textInputSetup(false,"Parent Name","txtParentName",$member->getParentName(),100);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtAddress1"])) {
                        echo textInputEmptyError(true, "Address Line 1", "txtAddress1", "errEmptyAddress1", "Please enter an Address Line 1", 50);
                    } else {
                        echo textInputPostback(true,"Address Line 1","txtAddress1",$_POST["txtAddress1"],50);
                    }
                } else {
                    echo textInputSetup(true,"Address Line 1","txtAddress1",$member->getAddress1(),50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"Address Line 2","txtAddress2",$_POST["txtAddress1"],50);
                } else {
                    echo textInputSetup(false,"Address Line 2","txtAddress2",$member->getAddress2(),50);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtCity"])) {
                        echo textInputEmptyError(true, "City", "txtCity", "errEmptyCity", "Please enter a City", 50);
                    } else {
                        echo textInputPostback(true,"City","txtCity",$_POST["txtCity"],50);
                    }
                } else {
                    echo textInputSetup(true,"City","txtCity",$member->getCity(),50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"County","txtCounty",$_POST["txtCounty"],50);
                } else {
                    echo textInputSetup(false,"County","txtCounty",$member->getCounty(),50);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtPostcode"])) {
                        echo textInputEmptyError(true, "Postcode", "txtPostcode", "errEmptyPostcode", "Please enter a Postcode", 8);
                    } else {
                        echo textInputPostback(true,"Postcode","txtPostcode",$_POST["txtPostcode"],8);
                    }
                } else {
                    echo textInputSetup(true,"Postcode","txtPostcode",$member->getPostcode(),8);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtTelephone"])) {
                        echo telInputEmptyError(true, "Telephone", "txtTelephone", "errEmptyTelephone", "Please enter a Telephone Number", 12);
                    } else {
                        echo telInputPostback(true,"Telephone","txtTelephone",$_POST["txtTelephone"],12);
                    }
                } else {
                    echo telInputSetup(true,"Telephone","txtTelephone",$member->getTelephone(),12);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    echo telInputPostback(false,"Mobile","txtMobile",$_POST["txtMobile"],12);
                } else {
                    echo telInputSetup(false,"Mobile","txtMobile",$member->getMobile(),12);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    echo emailInputPostback(false,"Email","txtEmail",$_POST["txtEmail"],250);
                } else {
                    echo emailInputSetup(false,"Email","txtEmail",$member->getEmail(),250);
                }
                
                echo '</fieldset></div><div class="large-12 medium-12 small-12 columns"><fieldset><legend>Swimming Details</legend>';
                echo '<div class="large-6 medium-6 small-12 columns">';

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"SASA Membership Number","txtSASANumber",$_POST["txtSASANumber"],15);              
                } else {
                    echo textInputSetup(false,"SASA Membership Number","txtSASANumber",$member->getSASANumber(),15);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo comboInputPostback(false, "Squad", "sltSquad", $_POST["sltSquad"], $squads->listAllSquads($conn));
                } else {
                    if (!is_null($member->getSquadID())) {
                        echo comboInputSetup(false,"Squad","sltSquad",$member->getSquadID(), $squads->listAllSquads($conn));
                    } else {
                        echo comboInputBlank(false,"Squad","sltSquad","Please select...", $squads->listAllSquads($conn));
                    }
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false, "Swimming Hours", "txtHours", $_POST["txtHours"],4);
                } else {
                    echo textInputSetup(false,"Swimming Hours","txtHours",$member->getSwimmingHours(),4);
                }

                echo '</div><div class="large-6 medium-6 small-12 columns">';

                if (isset($_POST["btnSubmit"])) {
                    if ($member->isMonthlyFeeValid($_POST['txtFees'])) {
                        echo moneyInputPostback(false, "Monthly Fee", "txtFees", $_POST["txtFees"],6);
                    } else {
                        echo moneyInputPostbackError(false, "Monthly Fee", "txtFees", $_POST["txtFees"],"errFees","Please enter a valid Fee",6);
                    }
                } else {
                    echo moneyInputSetup(false,"Monthly Fee","txtFees",$member->getMonthlyFee(),6);
                }

                if (isset($_POST["btnSubmit"])) {
                    if ($member->isFeeAdjustmentValid($_POST['txtAdjustment'])) {
                        echo moneyInputPostback(false, "Fee Adjustment", "txtAdjustment", $_POST["txtAdjustment"],6);
                    } else {
                        echo moneyInputPostbackError(false, "Fee Adjustment", "txtAdjustment", $_POST["txtAdjustment"],"errFees","Please enter a valid Fee Adjustment",6);
                    }
                } else {
                    echo moneyInputSetup(false,"Fee Adjustment","txtAdjustment",$member->getFeeAdjustment(),6);
                }

                echo '</div></fieldset></div>';
                echo '<div class="large-12 medium-12 small-12 columns"><fieldset><legend>Other Details</legend>';

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["chkRoles"])) {
                        echo checkboxInputEmptyError(true, "Role(s)", "chkRoles", "errEmptyRoles", "Please select at least one role", $roles->listAllRoles($conn));
                    } else {
                        echo checkboxInputPostback(true, "Role(s)", "chkRoles", $_POST['chkRoles'], $roles->listAllRoles($conn));
                    }
                } else {
                    echo checkboxInputSetup(true, "Role(s)", "chkRoles", $members_roles->getAllRolesForMember($conn,$member->getUsername()), $roles->listAllRoles($conn));
                }  

                if (isset($_POST["btnSubmit"])) {
                    echo textareaInputPostback(false, "Notes", "txtNotes", $_POST['txtNotes'], 2500, 8);
                } else {
                    echo textareaInputSetup(false, "Notes", "txtNotes", $member->getNotes(), 2500, 8);
                }                

                echo '</fieldset></div>';

                echo formEndWithButton("Save Changes","Delete");                

                dbClose($conn);
                ?> 
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
