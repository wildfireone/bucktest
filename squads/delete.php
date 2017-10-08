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

    if(!squadFullAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $squads = new Squads($_GET["id"]);
        
        if ($squads->delete($connection)) {
            $_SESSION['delete'] = true;
            
            header('Location:' .$domain . 'squads.php');
            die();
        } else {
            $_SESSION['error'] = true;
        }
        dbClose($connection);
    }    
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Delete | Squads | Bucksburn Amateur Swimming Club</title>
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
                <li class="current">Delete a Squad</li>
            </ul>
            
            <h2>Delete Squad</h2>
                      
            <?php
                require '../inc/forms.inc.php';
            
                $conn = dbConnect();

                $squad = new Squads($_GET["id"]);
                $squad->getAllDetails($conn);  

                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the squad. Please try again.</p>';
                    unset($_SESSION['error']);
                }               
                
                echo formStart(false);

                echo '<div class="panel"><p class="centre middle">Are you sure you want to delete the squad <b>' . $squad->getSquad() . '</b>?</p></div>';

                echo formEndWithDeleteButton("Delete");

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
