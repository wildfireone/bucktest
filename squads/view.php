<?php 
    session_start(); 

    // Check for a parameter before we send the header
    if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {        
            header( 'Location:' . $domain . '404.php' );
            die();
    }

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';

    if(!squadFullAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en-GB">

<head>    
    <?php include '../inc/meta.inc.php'; ?>
    <title>View | Squads | Bucksburn Amateur Swimming Club</title>   
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>    
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
                <li class="current">View a Squad</li>
            </ul>
        
            <h2>View a Squad</h2>    
            
             <?php
                    if (isset($_SESSION['update'])) {
                        echo '<p class="alert-box success radius centre">Changes saved!</p>';
                        unset($_SESSION['update']);
                    }

                    if (isset($_SESSION['create'])) {
                        echo '<p class="alert-box success radius centre">Squad created successfully!</p>';
                        unset($_SESSION['create']);
                    }
            ?>
                            
            <?php
                    require '../obj/squads.obj.php';
                    require_once '../obj/members_roles.obj.php';
                    require '../inc/forms.inc.php';

                    $conn = dbConnect();

                    $squadItem = new Squads($_GET["id"]);                    
                    $squadItem->getAllDetails($conn);   
                    $roles = new Members_Roles();

                    echo formStart();
                    echo '<div class="large-9 large-centered medium-12 small-12 columns"><fieldset><legend>Squad Details</legend>';

                    echo textInputSetup(true, "Squad ID", "txtID", $squadItem->getID(), 3, true);
                    echo textInputSetup(true, "Squad Name", "txtSquad", $squadItem->getSquad(), 50, true);
                    echo textareaInputSetup(false, "Squad Description", "txtDescription", $squadItem->getDescription(), 250,3,true);
                    echo comboInputSetup(true, "Squad Coach", "sltCoach", $squadItem->getCoach(), $roles->listAllCoaches($conn), true);

                    echo '</div></fieldset>';

                    echo linkButton("Edit this Squad", "edit.php?id=".$squadItem->getID());   

                    echo formEnd();

                    dbClose($conn);
                ?> 
                    
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
