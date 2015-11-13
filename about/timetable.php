<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Timetable | Bucksburn Amateur Swimming Club</title> 
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
            <li><a href="../about.php" role="link">About</a></li>
            <li class="current">Timetable</li>
        </ul>

        <?php
            if (isset($_SESSION['create'])) {
                echo '<p class="alert-box success radius centre">Timetable Item created successfully!</p>';
                unset($_SESSION['create']);
            }

            if (isset($_SESSION['update'])) {
                echo '<p class="alert-box success radius centre">Timetable Item updated successfully!</p>';
                unset($_SESSION['update']);
            }

            if (isset($_SESSION['delete'])) {
                echo '<p class="alert-box success radius centre">Timetable Item deleted successfully!</p>';
                unset($_SESSION['delete']);
            }

        ?>
        
        <h2>Timetable</h2>

        <p>Below is our club's timetable which shows when and where each of our squads train.</p>
                        
            <?php
                require '../inc/connection.inc.php';
                require '../inc/forms.inc.php';
                require '../obj/timetable.obj.php';
                require '../obj/days.obj.php';
                require '../obj/venues.obj.php';
                require '../obj/squads.obj.php';

                $conn = dbConnect();

                $timetableItem = New Timetable();
                $timetables = $timetableItem->getTimeSlots($conn); 
                $day = New Days();
                $venue = New Venues();
                $squad = New Squads();

               echo '<table class="large-12 medium-12 small-12 columns">
                    <tr>               
                        <th>Day</th>
                        <th>Venue</th>
                        <th>Time</th>
                        <th>Squads</th>
                    </tr>';
                
                foreach($timetables as $timetable) {
                    $day->setID($timetable["dayID"]);
                    $day->getAllDetails($conn);
                    $venue->setID($timetable["venueID"]);
                    $venue->getAllDetails($conn);  
                        
                    echo '<tr>
                        <td data-th="Day">' . $day->getDay() . '</td>
                        <td data-th="Venue">' . $venue->getVenue() . '</td>
                        <td data-th="Time">' . $timetable["time"] . '</td>';
                    
                    $squads = $squad->listSquadsForSession($conn, $day->getID(), $venue->getID(), $timetable["time"]);
                    
                    $squadList = '';
                    
                    foreach ($squads as $key => $value) {
                        $squad->setID($value);
                        $squad->getAllDetails($conn);
                        $squadList .= $squad->getSquad();
                        $squadList .= ', ';
                    }                    
                    
                    echo '<td  data-th="Squads">';
                    echo substr($squadList, 0, (strlen($squadList)-2));
                    echo '</td>';
                    if (isset($_SESSION['username'])) {
                        echo '<td><a href="' . $domain . 'about/timetable/edit.php?dayID=' . $day->getID() . '&venueID=' . $venue->getID() . '&time=' . htmlspecialchars($timetable["time"]) . '">Edit</a></td>';
                    }
                    echo '</tr>';   
                }
                echo '</table>';

                if (isset($_SESSION['username'])) {
                    echo '<div class="row">';
                    echo linkButton("Add New Timetable Item", $domain . "about/timetable/create.php");
                    echo '</div>';
                }
 
            ?>
            
        
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
