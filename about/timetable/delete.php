<?php 
    session_start();

    require '../../inc/connection.inc.php';
    require '../../inc/security.inc.php';
    require '../../obj/timetable.obj.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!timetableFullAccess($connection, $currentUser, $memberValidation)) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    $connection = dbConnect();
    $timetable = new Timetable();

    if (isset($_POST['btnSubmit'])) {

        $timetable->setDayID($_GET["dayID"]);
        $timetable->setVenueID($_GET["venueID"]);
        $timetable->setTime($_GET["time"]);

        if ($timetable->delete($connection)) {
            $_SESSION['delete'] = true;

            header('Location:' .$domain . 'about/timetable.php');
            die();
        } else {
            $_SESSION['error'] = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../../inc/meta.inc.php';?>
    <title>Delete | Timetable Item | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <link href="../../css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include '../../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="../../index.php" role="link">Home</a></li>
                <li><a href="../timetable.php" role="link">Timetable</a></li>
                <li class="current">Delete a Timetable Item</li>
            </ul>
            
            <h2>Delete a Timetable Item</h2>
            
            <?php
                require '../../inc/forms.inc.php';
                require '../../obj/days.obj.php';
                require '../../obj/venues.obj.php';
                require '../../obj/squads.obj.php';

                $conn = dbConnect();
                
                $day = new Days($_GET['dayID']);
                $day->getAllDetails($conn);
                $venue = new Venues($_GET['venueID']);
                $venue->getAllDetails($conn);
                
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the Gala Event. Please try again.</p>';
                    unset($_SESSION['error']);
                }
    
                echo formStart(false);

                echo '<div class="panel"><p class="centre">Are you sure you want to delete the Timetable Item on <b>' . $day->getDay() . '</b> from <b>' . $_GET["time"] . '</b> at <b>' . $venue->getVenue() . '</b>?</p></div>';
                
                echo formEndWithDeleteButton("Delete");
            
                ?>
                
        </div>
    </div>
    <?php include '../../inc/footer.inc.php';?>
</body>

</html>
