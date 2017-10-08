<?php 
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/gala_events.obj.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }


    if (is_null($_GET["id"]) || is_null($_GET["galaID"])) {        
        header( 'Location:' . $domain . '404.php' );
        exit;
    } else {
        $galas = new GalaEvents($_GET["id"], $_GET["galaID"]);
        if (!$galas->doesExist($connection)) {
            header( 'Location:' . $domain . '404.php' );
            exit;
        }

        if(!galaFullAccess($connection,$currentUser,$memberValidation))
        {
            header( 'Location:' . $domain . 'message.php?id=badaccess' );
            exit;
        }
    }

    if (isset($_POST['btnSubmit'])) {
        if ($galas->delete($connection)) {
            $_SESSION['delete'] = true;

            header('Location:' .$domain . 'gala-events.php?id=' . $_GET["galaID"]);
            die();
        } else {
            $_SESSION['error'] = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Delete | Gala Event | Bucksburn Amateur Swimming Club</title>
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
                <li><a href="../galas.php" role="link">Galas</a></li>
                <li class="current">Delete a Gala Event</li>
            </ul>
            
            <h2>Delete a Gala Event</h2>
            
            <?php
                require '../inc/forms.inc.php'; 
                require '../obj/galas.obj.php';            

                $conn = dbConnect();

                $event = new GalaEvents($_GET["id"], $_GET["galaID"]);
                $event->getAllDetails($conn,$_GET["galaID"]);

                $gala = new Galas($event->getGalaID());
                $gala->getAllDetails($conn);
                
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the Gala Event. Please try again.</p>';
                    unset($_SESSION['error']);
                }
    
                echo formStart(false);
                echo '<div class="panel"><p class="centre">Are you sure you want to delete the gala event <b>"' . $event->getID() . '"</b> from the Gala <b>"' . $gala->getTitle() . '"</b>?</p></div>';
                echo formEndWithDeleteButton("Delete");
                dbClose($conn);
                ?>
                
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
