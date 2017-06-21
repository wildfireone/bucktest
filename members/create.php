<?php
session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require_once '../obj/members.obj.php';
require_once '../obj/members_roles.obj.php';

if (!isset($_SESSION['username'])) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}

//No Full access? Show 403 error message
if (!memberFullAccess($connection, $currentUser, $memberValidation)) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}


if (isset($_POST['btnSubmit'])) {

    $member_Validation = new Members();
    $members_rolesValidation = new Members_Roles();


//if ($member_Validation->isInputValid($connection, $_POST['txtUsername'],$_POST['txtSASANumber'],$_POST['sltStatus'],$_POST['txtFirstName'],$_POST['txtMiddleName'],$_POST['txtLastName'],$_POST['sltGender'],$_POST['txtDOB'],$_POST['txtAddress1'],$_POST['txtAddress2'],$_POST['txtCity'],$_POST['txtCounty'],$_POST['txtPostcode'],$_POST['txtTelephone'],$_POST['txtMobile'],$_POST['txtEmail'],$_POST['txtParentTitle'],$_POST['txtParentName'],$_POST['sltSquad'],$_POST['txtRegisterDate'],null,$_POST['txtFees'], $_POST['txtAdjustment'],$_POST['txtHours'],$_POST['txtNotes']) && count($_POST['chkRoles']) > 0) {

//Check if both passwords match first
    if ($_POST["txtPassword"] == $_POST["txtPasswordConfirm"]) {
        {
            $errors = array();
            $member_Validation->checkPassword($_POST['txtPassword'], $errors);
            //Check if password meets complexity requirements
            if (sizeof($errors) <= 0) {
                //Check if username and roles are valid
                if ($member_Validation->isUsernameValid($connection, $_POST['txtUsername']) && (isset($_POST['chkRoles'])) && (count($_POST['chkRoles'])) > 0) {


                    $member_Validation->setUsername($_POST['txtUsername']);
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
                    $reset = (isset($_POST['chkReset']));
                    $member_Validation->setReset($reset);

                    if ($member_Validation->create($connection, $_POST['txtPassword'])) {
                        if ($member_Validation->createMember($connection)) {
                            echo "Create member success";

                            $members_rolesValidation->setMember($member_Validation->getUsername());

                            $roles = array();
                            foreach ($_POST['chkRoles'] as $key => $value) {
                                array_push($roles, $value);
                            }
                            foreach ($roles as $role) {
                                $members_rolesValidation->setRoleID($role);
                                $members_rolesValidation->create($connection);
                            }

                            $_SESSION['create'] = true;
                            header('Location:' . $domain . 'members/view.php?u=' . $member_Validation->getUsername());
                            die();
                        }

                    } else {
                        $_SESSION['error'] = true;
                        echo "Member not added";
                    }
                } else {
                    $_SESSION['invalid'] = true;
                }
            } else {
                $_SESSION['failed'] = true;
            }
        }
    } else {
        $_SESSION['invalidPass'] = true;
    }


}

?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Create | Members | Bucksburn Amateur Swimming Club</title>
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
<?php include '../inc/header.inc.php'; ?>
<br>

<div class="row" id="content">
    <div class="large-12 medium-12 small-12 columns">

        <ul class="breadcrumbs">
            <li><a href="../index.php" role="link">Home</a></li>
            <li><a href="../members.php" role="link">Members</a></li>
            <li class="current">Create a Member</li>
        </ul>

        <h2>Create a Member</h2>

        <?php
        require '../inc/forms.inc.php';
        require '../obj/status.obj.php';
        require '../obj/roles.obj.php';
        require '../obj/squads.obj.php';

        $conn = dbConnect();


        if (isset($_SESSION['invalidPass'])) {
            echo '<p class="alert-box error radius centre">Passwords do not match, please try again.</p>';
            unset($_SESSION['invalidPass']);
        }

        if (isset($_SESSION['invalid'])) {
            echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
            unset($_SESSION['invalid']);
        }

        if (isset($_SESSION['create'])) {
            echo '<p class="alert-box success radius centre">Member added successfully!</p>';
            unset($_SESSION['create']);
        }

        if (isset($_SESSION['failed'])) {
            echo '<p class="alert-box error radius centre">Password failed complexity checks, please see below:</p>';
            echo '<ul>';
            foreach ($errors as $value) {
                echo '<li>' . $value . '</li>';
            }
            echo '</ul>';
            unset($_SESSION['failed']);
        }


        if (isset($_SESSION['error'])) {
            echo '<p class="alert-box error radius centre">There was an error adding the new member. Please try again.</p>';
            unset($_SESSION['error']);
        }

        $member = New Members();
        $status = New Status();
        $roles = New Roles();
        $squads = New Squads();
        $members_roles = New Members_Roles();

        echo formStart();

        echo '<div class="large-6 medium-6 small-12 left"><fieldset><legend>Personal Details</legend>';

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtFirstName"])) {
                echo textInputEmptyError(true, "First Name", "txtFirstName", "errEmptyFirstName", "Please enter a First Name", 50);
            } else {
                echo textInputPostback(true, "First Name", "txtFirstName", $_POST["txtFirstName"], 50);
            }
        } else {
            echo textInputBlank(true, "First Name", "txtFirstName", 50);
        }

        if (isset($_POST["btnSubmit"])) {
            echo textInputPostback(false, "Middle Name", "txtMiddleName", $_POST["txtMiddleName"], 50);
        } else {
            echo textInputBlank(false, "Middle Name", "txtMiddleName", 50);
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtLastName"])) {
                echo textInputEmptyError(true, "Last Name", "txtLastName", "errEmptyLastName", "Please enter a Last Name", 50);
            } else {
                echo textInputPostback(true, "Last Name", "txtLastName", $_POST["txtLastName"], 50);
            }
        } else {
            echo textInputBlank(true, "Last Name", "txtLastName", 50);
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtUsername"])) {
                echo textInputEmptyError(true, "Username", "txtUsername", "errEmptyUsername", "Please enter a Username", 8);
            } else {
                if ($member->isUsernameValid($conn, $_POST['txtUsername'])) {
                    echo textInputPostback(true, "Username", "txtUsername", $_POST["txtUsername"], 8);
                } else {
                    echo textInputPostbackError(true, "Username", "txtUsername", "errErrUsername", "This username is already taken. Please enter a unique username", "This username is already taken. Please enter a unique username", 8);
                }
            }
        } else {
            echo textInputBlank(true, "Username", "txtUsername", 8);
        }

        if (isset($_POST["btnSubmit"])) {
            echo passwordInputEmptyError(true, "Password", "txtPassword", "errEmptyPassword", "Please enter a Password", 16);
        } else {
            echo passwordInputBlank(true, "Password", "txtPassword", 16);
        }

        echo passwordInputBlank(true, "Confirm Password", "txtPasswordConfirm", 32);

        echo '<div class="large-12 medium-12 small-12 columns">
                <label><b>
                    Password Reset Required After Login? </b>
                <input id="chkReset" type="checkbox" name="chkReset" value="1"/>
                </label>
                </div>';

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtDOB"])) {
                echo dateInputEmptyError(true, "Date of Birth", "txtDOB", "errEmptyDOB", "Please enter a Date of Birth", null, date("Y-m-d"));
            } else {
                echo dateInputPostback(true, "Date of Birth", "txtDOB", $_POST["txtDOB"], null, date("Y-m-d"));
            }
        } else {
            echo dateInputBlank(true, "Date of Birth", "txtDOB", null, date("Y-m-d"));
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["sltGender"])) {
                echo comboInputEmptyError(true, "Gender", "sltGender", "Please select...", "errEmptyGender", "Please select a Gender", $member->listGenders());
            } else {
                echo comboInputPostback(true, "Gender", "sltGender", $_POST["sltGender"], $member->listGenders());
            }
        } else {
            echo comboInputBlank(true, "Gender", "sltGender", "Please select...", $member->listGenders());
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["sltStatus"])) {
                echo comboInputEmptyError(true, "Member Status", "sltStatus", "Please select...", "errEmptyStatus", "Please select a Status", $status->listAllStatus($conn));
            } else {
                echo comboInputPostback(true, "Member Status", "sltStatus", $_POST["sltStatus"], $status->listAllStatus($conn));
            }
        } else {
            echo comboInputBlank(true, "Member Status", "sltStatus", "Please select...", $status->listAllStatus($conn));
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtRegisterDate"])) {
                echo dateInputEmptyError(true, "Date Joined", "txtRegisterDate", "errEmptyRegisterDate", "Please enter a Date Joined", null, null);
            } else {
                echo dateInputPostback(true, "Date Joined", "txtRegisterDate", $_POST["txtRegisterDate"], null, null);
            }
        } else {
            echo dateInputBlank(true, "Date Joined", "txtRegisterDate", null, null);
        }

        echo '</fieldset></div><div class="large-6 medium-6 small-12 right"><fieldset><legend>Contact Details</legend>';

        if (isset($_POST["btnSubmit"])) {
            echo textInputPostback(false, "Parent Title</b> (Mr, Mrs or Ms)", "txtParentTitle", $_POST["txtParentTitle"], 4);
        } else {
            echo textInputBlank(false, "Parent Title</b> (Mr, Mrs or Ms)", "txtParentTitle", 4);
        }

        if (isset($_POST["btnSubmit"])) {
            echo textInputPostback(false, "Parent Name", "txtParentName", $_POST["txtParentName"], 100);
        } else {
            echo textInputBlank(false, "Parent Name", "txtParentName", 100);
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtAddress1"])) {
                echo textInputEmptyError(true, "Address Line 1", "txtAddress1", "errEmptyAddress1", "Please enter an Address Line 1", 50);
            } else {
                echo textInputPostback(true, "Address Line 1", "txtAddress1", $_POST["txtAddress1"], 50);
            }
        } else {
            echo textInputBlank(true, "Address Line 1", "txtAddress1", 50);
        }

        if (isset($_POST["btnSubmit"])) {
            echo textInputPostback(false, "Address Line 2", "txtAddress2", $_POST["txtAddress1"], 50);
        } else {
            echo textInputBlank(false, "Address Line 2", "txtAddress2", 50);
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtCity"])) {
                echo textInputEmptyError(true, "City", "txtCity", "errEmptyCity", "Please enter a City", 50);
            } else {
                echo textInputPostback(true, "City", "txtCity", $_POST["txtCity"], 50);
            }
        } else {
            echo textInputBlank(true, "City", "txtCity", 50);
        }

        if (isset($_POST["btnSubmit"])) {
            echo textInputPostback(false, "County", "txtCounty", $_POST["txtCounty"], 50);
        } else {
            echo textInputBlank(false, "County", "txtCounty", 50);
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtPostcode"])) {
                echo textInputEmptyError(true, "Postcode", "txtPostcode", "errEmptyPostcode", "Please enter a Postcode", 8);
            } else {
                echo textInputPostback(true, "Postcode", "txtPostcode", $_POST["txtPostcode"], 8);
            }
        } else {
            echo textInputBlank(true, "Postcode", "txtPostcode", 8);
        }

        if (isset($_POST["btnSubmit"])) {
            if (empty($_POST["txtTelephone"])) {
                echo telInputEmptyError(true, "Telephone", "txtTelephone", "errEmptyTelephone", "Please enter a Telephone Number", 12);
            } else {
                echo telInputPostback(true, "Telephone", "txtTelephone", $_POST["txtTelephone"], 12);
            }
        } else {
            echo telInputBlank(true, "Telephone", "txtTelephone", 12);
        }

        if (isset($_POST["btnSubmit"])) {
            echo telInputPostback(false, "Mobile", "txtMobile", $_POST["txtMobile"], 12);
        } else {
            echo telInputBlank(false, "Mobile", "txtMobile", 12);
        }

        if (isset($_POST["btnSubmit"])) {
            echo emailInputPostback(false, "Email", "txtEmail", $_POST["txtEmail"], 250);
        } else {
            echo emailInputBlank(false, "Email", "txtEmail", 250);
        }

        echo '</fieldset></div><div class="large-12 medium-12 small-12 columns"><fieldset><legend>Swimming Details</legend>';
        echo '<div class="large-6 medium-6 small-12 columns">';

        if (isset($_POST["btnSubmit"])) {
            echo textInputPostback(false, "SASA Membership Number", "txtSASANumber", $_POST["txtSASANumber"], 15);
        } else {
            echo textInputBlank(false, "SASA Membership Number", "txtSASANumber", 15);
        }

        if (isset($_POST["btnSubmit"])) {
            echo comboInputPostback(false, "Squad", "sltSquad", $_POST["sltSquad"], $squads->listAllSquads($conn));
        } else {
            echo comboInputBlank(false, "Squad", "sltSquad", "Please select...", $squads->listAllSquads($conn));
        }

        if (isset($_POST["btnSubmit"])) {
            echo textInputPostback(false, "Swimming Hours", "txtHours", $_POST["txtHours"], 4);
        } else {
            echo textInputBlank(false, "Swimming Hours", "txtHours", 4);
        }

        echo '</div><div class="large-6 medium-6 small-12 columns">';

        if (isset($_POST["btnSubmit"])) {
            if ($member->isMonthlyFeeValid($_POST['txtFees'])) {
                echo moneyInputPostback(false, "Monthly Fee", "txtFees", $_POST["txtFees"], 6);
            } else {
                if (!empty($_POST["txtFees"])) {
                    echo moneyInputPostbackError(false, "Monthly Fee", "txtFees", $_POST["txtFees"], "errFees", "Please enter a valid Fee", 6);
                } else {
                    echo moneyInputBlank(false, "Monthly Fee", "txtFees", 6);
                }
            }
        } else {
            echo moneyInputBlank(false, "Monthly Fee", "txtFees", 6);
        }

        if (isset($_POST["btnSubmit"])) {
            if ($member->isFeeAdjustmentValid($_POST['txtAdjustment'])) {
                echo moneyInputPostback(false, "Fee Adjustment", "txtAdjustment", $_POST["txtAdjustment"], 6);
            } else {
                if (!empty($_POST["txtAdjustment"])) {
                    echo moneyInputPostbackError(false, "Fee Adjustment", "txtAdjustment", $_POST["txtAdjustment"], "errFees", "Please enter a valid Fee Adjustment", 6);
                } else {
                    echo moneyInputBlank(false, "Fee Adjustment", "txtAdjustment", 6);
                }
            }
        } else {
            echo moneyInputBlank(false, "Fee Adjustment", "txtAdjustment", 6);
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
            echo checkboxInputBlank(true, "Role(s)", "chkRoles", $roles->listAllRoles($conn));
        }

        if (isset($_POST["btnSubmit"])) {
            echo textareaInputPostback(false, "Notes", "txtNotes", $_POST['txtNotes'], 2500, 8);
        } else {
            echo textareaInputBlank(false, "Notes", "txtNotes", 2500, 8);
        }

        echo '</fieldset></div>';

        echo formEndWithButton("Add new member");

        ?>

    </div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>
