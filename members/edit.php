<?php 
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require_once '../obj/members.obj.php';
    require_once '../obj/members_roles.obj.php';

    $limited_edit = false;

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

        //No Full access? Show 403 error message
        if(!memberFullAccess($connection,$currentUser,$memberValidation)) {
        //Not full access and editing someone else's profile - see 403 page
             if($_SESSION['username'] !== $_GET["u"]){
                header( 'Location:' . $domain . 'message.php?id=badaccess' );
                exit;
            }
            else if($_SESSION['username'] == $members->getUsername()) {
                $limited_edit = true;
            }
        }
    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $member_Validation = new Members($_GET["u"]);
        $members_rolesValidation = new Members_Roles();


        //if ($member_Validation->isInputValid($connection, $_POST['txtUsername'],$_POST['txtSASANumber'],$_POST['sltStatus'],$_POST['txtFirstName'],$_POST['txtMiddleName'],$_POST['txtLastName'],$_POST['sltGender'],$_POST['txtDOB'],$_POST['txtAddress1'],$_POST['txtAddress2'],$_POST['txtCity'],$_POST['txtCounty'],$_POST['txtPostcode'],$_POST['txtTelephone'],$_POST['txtMobile'],$_POST['txtEmail'],$_POST['txtParentTitle'],$_POST['txtParentName'],$_POST['sltSquad'],$_POST['txtRegisterDate'],null,$_POST['txtFees'], $_POST['txtAdjustment'],$_POST['txtHours'],$_POST['txtNotes']) && count($_POST['chkRoles']) > 0) {


        if(!$limited_edit) {

            if ((isset($_POST['chkRoles'])) && count($_POST['chkRoles']) > 0) {
                $member_Validation->setSASANumber($_POST['txtSASANumber']);
                $member_Validation->setStatus($_POST['sltStatus']);
                $member_Validation->setFirstName($_POST['txtFirstName']);
                $member_Validation->setMiddleName($_POST['txtMiddleName']);
                $member_Validation->setLastName($_POST['txtLastName']);
                $member_Validation->setGender($_POST['sltGender']);
                $member_Validation->setDOB($_POST['txtDOB']);
                $member_Validation->setAddress1($_POST['txtAddress1']);
                $member_Validation->setAddress2($_POST['txtAddress2']);
                $member_Validation->setCity($_POST['txtCity']);
                $member_Validation->setCounty($_POST['txtCounty']);
                $member_Validation->setPostcode($_POST['txtPostcode']);
                $member_Validation->setTelephone($_POST['txtTelephone']);
                $member_Validation->setMobile($_POST['txtMobile']);
                $member_Validation->setEmail($_POST['txtEmail']);
                $member_Validation->setParentTitle($_POST['txtParentTitle']);
                $member_Validation->setParentName($_POST['txtParentName']);
                $member_Validation->setSquadID($_POST['sltSquad']);
                $member_Validation->setRegisterDate($_POST['txtRegisterDate']);
                $member_Validation->setLastLoginDate(null);
                $member_Validation->setMonthlyFee($_POST['txtFees']);
                $member_Validation->setFeeAdjustment($_POST['txtAdjustment']);
                $member_Validation->setSwimmingHours($_POST['txtHours']);
                $member_Validation->setNotes($_POST['txtNotes']);

                //Finally update user information before updating user roles..

                if ($member_Validation->update($connection)) {
                    $_SESSION['update'] = true;
                    //Other Details
                    $roles = array();

                    //Delete current roles:
                    $members_rolesValidation->delete($connection, $member_Validation->getUsername());

                    foreach ($_POST['chkRoles'] as $key => $value) {
                        array_push($roles, $value);
                    }


                    foreach ($roles as $role) {
                        $members_rolesValidation->setRoleID($role);
                        $members_rolesValidation->setMember($_GET["u"]);
                        $members_rolesValidation->create($connection);
                    }

                    if ($_SESSION['update']) {
                        header('Location:' . $domain . 'members/view.php?u=' . $member_Validation->getUsername());
                        die();
                    }

                }

            }
        }
        else if($limited_edit) {
            $member_Validation->setStatus($_POST['sltStatus']);
            $member_Validation->setFirstName($_POST['txtFirstName']);
            $member_Validation->setMiddleName($_POST['txtMiddleName']);
            $member_Validation->setLastName($_POST['txtLastName']);
            $member_Validation->setGender($_POST['sltGender']);
            $member_Validation->setDOB($_POST['txtDOB']);
            $member_Validation->setAddress1($_POST['txtAddress1']);
            $member_Validation->setAddress2($_POST['txtAddress2']);
            $member_Validation->setCity($_POST['txtCity']);
            $member_Validation->setCounty($_POST['txtCounty']);
            $member_Validation->setPostcode($_POST['txtPostcode']);
            $member_Validation->setTelephone($_POST['txtTelephone']);
            $member_Validation->setMobile($_POST['txtMobile']);
            $member_Validation->setEmail($_POST['txtEmail']);
            $member_Validation->setParentTitle($_POST['txtParentTitle']);
            $member_Validation->setParentName($_POST['txtParentName']);
            $member_Validation->setRegisterDate($_POST['txtRegisterDate']);


            //Finally update user information before updating user roles..

            if ($member_Validation->update($connection)) {
                $_SESSION['update'] = true;
            }

            if ($_SESSION['update']) {
                header('Location:' . $domain . 'my-details.php');
                die();
            }

        }
        else {
            $_SESSION['invalid'] = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Edit | Members | Bucksburn Amateur Swimming Club</title>
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>   
    <link href="../css/site.css" rel="stylesheet"/>
    <script src='../tinymce/tinymce.min.js'></script>
    <script>
        tinymce.init({
            selector: '#txtNotes',
            plugins: 'advlist, table, autolink, code, contextmenu, imagetools, fullscreen, hr,  colorpicker, preview, spellchecker, link, autosave, lists, visualblocks'

        });
    </script>
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
                

                    echo textInputSetup(false,"Username","txtUsername",$member->getUsername(),8,true);

              /*  if (isset($_POST["btnSubmit"])) {
                    echo passwordInputEmptyError(false, "Password", "txtPassword", "errEmptyPassword", "Please enter a Password", 16);
                } else {
                    echo passwordInputBlank(false,"Password","txtPassword",16);
                }*/
                
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

                //Standard Members cannot edit their own roles/swimming details.
                if(!$limited_edit) {

                    echo '</fieldset></div><div class="large-12 medium-12 small-12 columns"><fieldset><legend>Swimming Details</legend>';
                    echo '<div class="large-6 medium-6 small-12 columns">';

                    if (isset($_POST["btnSubmit"])) {
                        echo textInputPostback(false, "SASA Membership Number", "txtSASANumber", $_POST["txtSASANumber"], 15);
                    } else {
                        echo textInputSetup(false, "SASA Membership Number", "txtSASANumber", $member->getSASANumber(), 15);
                    }

                    if (isset($_POST["btnSubmit"])) {
                        echo comboInputPostback(false, "Squad", "sltSquad", $_POST["sltSquad"], $squads->listAllSquads($conn));
                    } else {
                        if (!is_null($member->getSquadID())) {
                            echo comboInputSetup(false, "Squad", "sltSquad", $member->getSquadID(), $squads->listAllSquads($conn));
                        } else {
                            echo comboInputBlank(false, "Squad", "sltSquad", "Please select...", $squads->listAllSquads($conn));
                        }
                    }

                    if (isset($_POST["btnSubmit"])) {
                        echo textInputPostback(false, "Swimming Hours", "txtHours", $_POST["txtHours"], 4);
                    } else {
                        echo textInputSetup(false, "Swimming Hours", "txtHours", $member->getSwimmingHours(), 4);
                    }

                    echo '</div><div class="large-6 medium-6 small-12 columns">';

                    if (isset($_POST["btnSubmit"])) {
                        if ($member->isMonthlyFeeValid($_POST['txtFees'])) {
                            echo moneyInputPostback(false, "Monthly Fee", "txtFees", $_POST["txtFees"], 6);
                        } else {
                            echo moneyInputPostbackError(false, "Monthly Fee", "txtFees", $_POST["txtFees"], "errFees", "Please enter a valid Fee", 6);
                        }
                    } else {
                        echo moneyInputSetup(false, "Monthly Fee", "txtFees", $member->getMonthlyFee(), 6);
                    }

                    if (isset($_POST["btnSubmit"])) {
                        if ($member->isFeeAdjustmentValid($_POST['txtAdjustment'])) {
                            echo moneyInputPostback(false, "Fee Adjustment", "txtAdjustment", $_POST["txtAdjustment"], 6);
                        } else {
                            echo moneyInputPostbackError(false, "Fee Adjustment", "txtAdjustment", $_POST["txtAdjustment"], "errFees", "Please enter a valid Fee Adjustment", 6);
                        }
                    } else {
                        echo moneyInputSetup(false, "Fee Adjustment", "txtAdjustment", $member->getFeeAdjustment(), 6);
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
                        echo checkboxInputSetup(true, "Role(s)", "chkRoles", $members_roles->getAllRolesForMember($conn, $member->getUsername()), $roles->listAllRoles($conn));
                    }

                    if (isset($_POST["btnSubmit"])) {
                        echo textareaInputPostback(false, "Notes", "txtNotes", $_POST['txtNotes'], 2500, 8);
                    } else {
                        echo textareaInputSetup(false, "Notes", "txtNotes", $member->getNotes(), 2500, 8);
                    }

                    echo '</fieldset></div>';
                    echo formEndWithButton("Save Changes","delete.php?u=" . $member->getUsername(), "view.php?u=" . $member->getUsername());
                }
                else{
                    echo '</fieldset></div>';
                    echo formEndWithButton("Save changes",false,"/my-details.php");
                }

                dbClose($conn);
                ?> 
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
