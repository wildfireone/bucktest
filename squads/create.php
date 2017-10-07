<?php
session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/squads.obj.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!squadFullAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $squad = new Squads();
        
        if ($squad->isInputValid($_POST['txtSquad'], $_POST['txtDescription'], $_POST['sltCoach'])) {
            $squad->setSquad($_POST["txtSquad"]);
            $squad->setDescription($_POST["txtDescription"]);
            $squad->setCoach($_POST["sltCoach"]);
            
            if ($squad->create($connection)) {
                $_SESSION['create'] = true;

                header('Location:' .$domain . 'squads/view.php?id=' . $squad->getID());
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
    <title>Create | Squads | Bucksburn Amateur Swimming Club</title>
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
                <li class="current">Create a Squad</li>
            </ul>
            
            <h2>Create Squad</h2>
    
            <?php                            
                require '../inc/forms.inc.php';
                require_once '../obj/members_roles.obj.php';

                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error creating the squad. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                $conn = dbConnect();
                $roles = new Members_Roles();

                echo formStart();
                echo '<div class="large-9 large-centered medium-12 small-12 columns"><fieldset><legend>Squad Details</legend>';

                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtSquad"])) {
                        echo textInputEmptyError(true, "Squad Name", "txtSquad", "errEmptySquad", "Please enter a Squad Name", 50);
                    } else {
                        echo textInputPostback(true, "Squad Name", "txtSquad", $_POST["txtSquad"], 50);
                    }
                } else {
                    echo textInputBlank(true, "Squad Name", "txtSquad", 50);
                }
        
                if (isset($_POST["btnSubmit"])) {                    
                    echo textareaInputPostback(false, "Squad Description", "txtDescription", $_POST["txtDescription"], 250,3);
                } else {
                    echo textareaInputBlank(false, "Squad Description", "txtDescription", 250,3);
                }

               if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltCoach"])) {
                        echo comboInputEmptyError(true, "Squad Coach", "sltCoach", "Please select...", "errEmptyCoach", "Please choose a Squad Coach", $roles->listAllCoaches($conn));
                    } else {
                        echo comboInputPostback(true, "Squad Coach", "sltCoach", $_POST["sltCoach"], $roles->listAllCoaches($conn));
                    }
                } else {
                    echo comboInputBlank(true, "Squad Coach", "sltCoach", "Please select...", $roles->listAllCoaches($conn));
                }

                echo '</fieldset></div>';
                echo formEndWithButton("Add Squad");
            ?>
                
        </div>
    </div>            
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
