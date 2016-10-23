<?php
    session_start();

    require '../../inc/connection.inc.php';
    require '../../inc/security.inc.php';
    require '../../obj/timetable.obj.php';
    
    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    $timetable = new Timetable();

    if (!isset($_POST["chkSquads"])) {
        $_POST["chkSquads"] = null;
    }

    if (isset($_POST["btnSubmit"])) {
        if ($timetable->isInputValid($_POST["chkSquads"],$_POST["sltDay"],$_POST["sltVenue"],$_POST["txtTime"])) {
            $connection = dbConnect();

            $timetable->setDayID($_GET["dayID"]);
            $timetable->setVenueID($_GET["venueID"]);
            $timetable->setTime($_GET["time"]);

            $timetable->delete($connection);

            $timetable->setDayID($_POST["sltDay"]);
            $timetable->setVenueID($_POST["sltVenue"]);
            $timetable->setTime($_POST["txtTime"]);
            
            foreach ($_POST["chkSquads"] as $key => $value) {
                $timetable->setSquadID($value);
                $timetable->create($connection);
            }
                
            $_SESSION['update'] = true;

            header('Location:' .$domain . 'about/timetable.php');
            die();
            
            dbClose($connection);
        } else {
            $_SESSION['invalid'] = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../../inc/meta.inc.php';?>
    <title>Edit | Timetable | Bucksburn Amatuer Swimming Club</title>  
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>     
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
                <li class="current">Edit a Timetable Item</li>
            </ul>
    
            <h2>Edit a Timetable Item</h2>
            
            <?php
                require '../../inc/forms.inc.php';
                require '../../obj/days.obj.php';
                require '../../obj/venues.obj.php';
                require '../../obj/squads.obj.php';

                $conn = dbConnect();
                
                $day = new Days($_GET['dayID']);
                $venue = new Venues($_GET['venueID']);
                $squad = new Squads();

                $squadList = $squad->listSquadsForSession($conn, $day->getID(), $venue->getID(), $_GET['time']);

                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error editing this Timetable Item. Please try again.</p>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }

                echo formStart();

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltDay"])) {
                        echo comboInputEmptyError(true, "Day", "sltDay", "Please select a Day...", "errEmptyDay","Please select a Day",$day->listAll($conn));
                    } else {
                        echo comboInputPostback(true, "Day", "sltDay", $_POST["sltDay"], $day->listAll($conn));
                    }                    
                } else {
                    echo comboInputSetup(true,"Day","sltDay",$day->getID(),$day->listAll($conn));
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltVenue"])) {
                        echo comboInputEmptyError(true, "Venue", "sltVenue", "Please select a Venue...", "errEmptyVenue","Please select a Venue",$venue->listAllVenues($conn));
                    } else {
                        echo comboInputPostback(true, "Venue", "sltVenue", $_POST["sltVenue"], $venue->listAllVenues($conn));
                    }                    
                } else {
                    echo comboInputSetup(true,"Venue","sltVenue",$venue->getID(),$venue->listAllVenues($conn));
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtTime"])) {
                        echo textInputEmptyError(true, "Time", "txtTime", "errEmptyTime","Please enter a Time",11);
                    } else {
                        echo textInputPostback(true, "Time", "txtTime", $_POST["txtTime"], 11);
                    }                    
                } else {
                    echo textInputSetup(true,"Time","txtTime",$_GET['time'],11);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["chkSquads"])) {
                        echo checkboxInputEmptyError(true, "Squads", "chkSquads", "errEmptyTime","Please select at least one Squad",$squad->listAllSquads($conn));
                    } else {
                        echo checkboxInputPostback(true, "Squads", "chkSquads", $_POST["chkSquads"], $squad->listAllSquads($conn));
                    }                    
                } else {
                    echo checkboxInputSetup(true,"Squads","chkSquads",$squadList,$squad->listAllSquads($conn));
                }

                echo formEndWithButton("Save changes", $domain . "about/timetable/delete.php?dayID=" . $day->getID() . "&venueID=" . $venue->getID() . "&time=" . $_GET['time']);

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../../inc/footer.inc.php';?>
</body>

</html>
