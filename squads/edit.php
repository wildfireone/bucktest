<?php 
    session_start(); 

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
    
    if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {        
            header( 'Location:' . $domain . '404.php' );
            die();
    }

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/squads.obj.php';
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $squad = new Squads($_GET["id"]);
        
        if ($squad->isInputValid($_POST['txtSquad'], $_POST['txtDescription'], $_POST['sltCoach'])) {
            $squad->setSquad($_POST['txtSquad']);
            $squad->setDescription($_POST['txtDescription']);
            $squad->setCoach($_POST['sltCoach']);
            
            if ($squad->update($connection)) {
                $_SESSION['update'] = true;
                
                header('Location:' .$domain . '/squads/view.php?id=' . $squad->getID());
                die();
            } else {
                $_SESSION['error'] = true;
            }
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
    <title>Edit | Squads | Bucksburn Amatuer Swimming Club</title>   
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
                <li><a href="../squads.php" role="link">Squads</a></li>
                <li class="current">Edit a Squad</li>
            </ul>
            
            <h2>Edit Squad</h2>
                      
            <?php
                require '../inc/forms.inc.php';
                require_once '../obj/members_roles.obj.php';
            
                $conn = dbConnect();

                $squadItem = new Squads($_GET["id"]);
                $squadItem->getAllDetails($conn);
                $roles = new Members_Roles();   

                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error updating the squad. Please try again.</p>';
                    unset($_SESSION['error']);
                }             
                
                echo formStart();
                echo '<div class="large-9 large-centered medium-12 small-12 columns"><fieldset><legend>Squad Details</legend>';
 
                echo textInputSetup (true, "Squad ID", "txtID", $squadItem->getID(), 3, true);
            
                if (isset($_POST["btnSubmit"])) {
                        if (empty($_POST["txtSquad"])) {
                            echo textInputEmptyError(true, "Squad Name", "txtSquad", "errEmptySquad", "Please enter a Squad Name", 50);
                        } else {
                            echo textInputPostback(true, "Squad Name", "txtSquad",  $_POST["txtSquad"], 50);                           
                        }
                } else {
                    echo textInputSetup(true, "Squad Name", "txtSquad", $squadItem->getSquad(), 50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textareaInputPostback(false, "Squad Description", "txtDescription", $_POST["txtDescription"], 250,3);
                } else {
                    echo textareaInputSetup(false, "Squad Description", "txtDescription", $squadItem->getDescription(), 250,3);
                } 

                if (isset($_POST["btnSubmit"])) {
                    echo comboInputPostback(true, "Squad Coach", "sltCoach", $_POST["sltCoach"], $roles->listAllCoaches($conn));
                } else {
                    echo comboInputSetup(true, "Squad Coach", "sltCoach", $squadItem->getCoach(), $roles->listAllCoaches($conn));
                }

                echo '</fieldset></div>';
                echo formEndWithButton("Save changes","delete.php?id=".$squadItem->getID());
                
                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
