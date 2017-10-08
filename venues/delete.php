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
    require '../obj/venues.obj.php';

    if(!venueFullAccess($connection, $currentUser, $memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $venues = new Venues($_GET["id"]);
        
        if ($venues->delete($connection)) {
            $_SESSION['delete'] = true;
            
            header('Location:' .$domain . 'venues.php');
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
    <title>Delete | Venues | Bucksburn Amateur Swimming Club</title>
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
                <li><a href="../venues.php" role="link">Venues</a></li>
                <li class="current">Delete a Venue</li>
            </ul>
            
            <h2>Delete a Venue</h2>
                      
            <?php
                require '../inc/forms.inc.php';
            
                $conn = dbConnect();

                $venue = new Venues($_GET["id"]);
                $venue->getAllDetails($conn);  

                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the squad. Please try again.</p>';
                    unset($_SESSION['error']);
                }               
                
                echo formStart(false);

                echo '<div class="panel"><p class="centre middle">Are you sure you want to delete the venue <b>' . $venue->getVenue() . '</b>? This will also delete all galas, gala events and swimming times that are associated with it. <br><br><b>This action cannot be undone.</b></p></div>';

                echo formEndWithDeleteButton("Delete");

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
