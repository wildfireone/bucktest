<?php 
    session_start();

    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>My Details | Members | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>    
    <link href="css/site.min.css" rel="stylesheet"/>
</head>

<body>   
    <?php include 'inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="index.php" role="link">Home</a></li>
                <li><a href="members.php" role="link">Members</a></li>
                <li class="current">My Details</li>
            </ul>
        
            <h2>My Details</h2>        
          
                <?php
                require 'inc/forms.inc.php';
                require_once 'obj/members.obj.php';
                require 'obj/status.obj.php';
                require 'obj/roles.obj.php';
                require_once 'obj/members_roles.obj.php';
                require 'obj/squads.obj.php';

                $conn = dbConnect();

                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">Changes saved!</p>';
                    unset($_SESSION['update']);
                }

                if (isset($_SESSION['updatePassword'])) {
                    echo '<p class="alert-box success radius centre">Password updated!</p>';
                    unset($_SESSION['updatePassword']);
                }

                $member = new Members($_SESSION["username"]);
                $member->getAllDetails($conn);
                
                $status = new Status($member->getStatus());
                $status->getAllDetails($conn);  

                $roles = New Roles();
                $squads = New Squads();
                $members_roles = New Members_Roles();
    
                echo '<h3>' . $member->getFirstName() . ' ' . $member->getLastName() . '</h3>';
                echo formStart();

                echo '<div class="large-6 medium-6 small-12 left"><fieldset><legend>Personal Details</legend>';

                echo textInputSetup(true,"First Name","txtFirstName",$member->getFirstName(),50,true);
                echo textInputSetup(false,"Middle Name","txtMiddleName",$member->getMiddleName(),50,true);
                echo textInputSetup(true,"Last Name","txtLastName",$member->getLastName(),50,true);
                echo textInputSetup(true,"Username","txtUsername",$member->getUsername(),8,true);
                echo dateInputSetup(true,"Date of Birth","txtDOB",$member->getDOB(),null,null,true);
                echo comboInputSetup(true,"Gender","sltGender",$member->getGender(),$member->listGenders(),true);

                echo comboInputSetup(true,"Member Status","txtStatus",$member->getStatus(),$status->listAllStatus($conn),true);
                echo textInputSetup(false,"SASA Membership","txtSASANumber",$member->getSASANumber(),15,true);
                echo comboInputSetup(true, "Squad", "sltSquad", $member->getSquadID(), $squads->listAllSquads($conn), true);
                echo dateInputSetup(true,"Date Joined","txtRegisterDate",$member->getRegisterDate(),null,null,true);
                
                echo '</fieldset></div><div class="large-6 medium-6 small-12 right"><fieldset><legend>Contact Details</legend>';

                echo textInputSetup(false,"Parent Title</b> (Mr, Mrs or Ms)","txtParentTitle",$member->getParentTitle(),4,true);
                echo textInputSetup(false,"Parent Name","txtParentName",$member->getParentName(),100,true);
                echo textInputSetup(true,"Address Line 1","txtAddress1",$member->getAddress1(),50,true);
                echo textInputSetup(false,"Address Line 2","txtAddress2",$member->getAddress2(),50,true);
                echo textInputSetup(true,"City","txtCity",$member->getCity(),50,true);
                echo textInputSetup(false,"County","txtCounty",$member->getCounty(),50,true);
                echo textInputSetup(true,"Postcode","txtPostcode",$member->getPostcode(),8,true);
                echo telInputSetup(true,"Telephone","txtTelephone",$member->getTelephone(),12,true);
                echo telInputSetup(false,"Mobile","txtMobile",$member->getMobile(),12,true);
                echo emailInputSetup(false,"Email","txtEmail",$member->getEmail(),250,true);
                
                echo '</fieldset></div>';
                echo checkboxInputSetup(true, "Role(s)", "chkRoles", $members_roles->getAllRolesForMember($conn,$member->getUsername()), $roles->listAllRoles($conn), true);
                echo textareaInputSetup(false, "Notes", "txtNotes", $member->getNotes(), 100, 8, true);
                echo linkButton("Update my details", "members/edit.php?u=".$member->getUsername());
                echo "<div> <br/>";
                echo linkButton("Update Password", "members/edit_pass.php?u=".$member->getUsername());
                echo formEnd();                

                dbClose($conn);
            ?>
              
        </div> 
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
