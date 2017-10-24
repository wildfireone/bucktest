<?php

//Debugging enable
ini_set('display_errors',1);
ini_set('display_startup_errors' ,1);
//error_reporting(E_ALL);

    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/galas.obj.php';

    if (is_null($_GET["id"])) {        
        header( 'Location:' . $domain . '404.php' );
        exit;
    } else {
        $connection = dbConnect();
        $galas = new Galas();
        if (!$galas->doesExist($connection,$_GET["id"])) {
            header( 'Location:' . $domain . '404.php' );
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>View Gala Results | Gala Results | Bucksburn Amateur Swimming Club</title>    
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
</head>

<body>   
    <?php include '../inc/header.inc.php'; ?>   
    <br>
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="../index.php" role="link">Home</a></li>
                <li><a href="../gala-results.php" role="link">Gala Results</a></li>
                <li class="current">View Gala Results</li>
            </ul>
        
            <?php
                require '../inc/forms.inc.php';
                require '../obj/venues.obj.php';
                require_once '../obj/members.obj.php';
                require '../obj/gala_events.obj.php';
                require '../obj/strokes.obj.php';
                require '../obj/lengths.obj.php';
                require '../obj/swim_times.obj.php';

                $conn = dbConnect();
                
                $venue = new Venues();
                $member = new Members();
                $event = new GalaEvents();
                $stroke = new Strokes();
                $length = new Lengths();
                $swim_time = new Swim_Times();
                
                $gala = new Galas();
                $gala->setID($_GET["id"]);
                $gala->getAllDetails($conn); 
                $venue->setID($gala->getVenueID());
                $venue->getAllDetails($conn);

                $strokes = $event->listStrokesForGala($conn,$gala->getID());

                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">The Swim Time was updated successfully!</p>';
                    unset($_SESSION['update']);
                }

                if (isset($_SESSION['delete'])) {
                    echo '<p class="alert-box success radius centre">The Swim Time was deleted successfully!</p>';
                    unset($_SESSION['delete']);
                }

                echo '<h2 id="results">' . $gala->getTitle() . ' Results</h2>';
                echo '<h3 class="h4 capitalise">' . $venue->getVenue() . '</h3>';
                echo '<h4 class="h5 italic">' . date("l jS F Y", strtotime($gala->getDate())) . '</h4>';

                echo '<p><b>Accredited</b>: ';
                if (!is_null($gala->getIsAccredited())) {
                    if ($gala->getIsAccredited()) {
                        echo 'Yes';
                    } else {
                        echo 'No';
                    }
                } else {
                    echo 'N/A';
                }
                echo '<br><b>Course Type</b>: ';
                if (!is_null($gala->getIsLongCourse())) {
                    if ($gala->getIsLongCourse()) {
                        echo 'Long Course';
                    } else {
                        echo 'Short Course';
                    }
                } else {
                    echo 'N/A';
                }
                echo '</p>';

                if (!$gala->doResultsExist($conn)) {
                    echo '<p class="centre"><b>Results for this gala are still to be posted. Please check back soon.</b></p>';
                } else {

                echo '<span><b>Skip to:</b></span><ol>';
                foreach ($strokes as $strokeItem) {
                    $stroke->setID($strokeItem["strokeID"]);
                    $stroke->getAllDetails($conn);
                    echo '<li><a href="#' . strtolower($stroke->getStroke()) . '">' . $stroke->getStroke() . '</a></li>';

                }
                echo '</ol>';

                if (isset($_SESSION['username']) && (galaFullAccess($connection,$currentUser,$memberValidation)))  {
                    echo linkButton("Edit Gala Events", "../gala-events.php?id=".$gala->getID(), true) . '<br>';
                }

                foreach ($strokes as $strokeItem) {
                    $stroke->setID($strokeItem["strokeID"]);
                    $stroke->getAllDetails($conn);

                    echo '<h5 class="h3 capitalise centre clearfix" id="' . strtolower($stroke->getStroke()) . '">' . $stroke->getStroke() . '</h5>';

                    echo '<table class="large-12 medium-12 small-12 columns">
                        <tr>
                            <th class="centre">Length</th>
                            <th class="centre">Age Group</th>
                            <th class="centre">Gender</th>
                            <th class="centre">Swimmer</th>
                            <th class="centre">Time</th>
                            <th class="centre">Rank</th>
                        <tr>';

                    $events = $event->listAllGalaEventsByStroke($conn,$gala->getID(),$stroke->getID());

                    foreach ($events as $eventItem) {
                        $event->setID($eventItem["id"]);
                        $event->getAllDetails($conn,$gala->getID());
                        $length->setID($event->getLengthID());
                        $length->getAllDetails($conn);

                        $swim_times = $swim_time->listAllSwimTimesForGalaByEvent($conn,$gala->getID(),$event->getID());

                        $iCount = 0;

                        foreach ($swim_times as $swim_timeItem) {
                            $swim_time->setPKs($swim_timeItem["member"],$gala->getID(),$event->getID());
                            $swim_time->getAllDetails($conn);
                            $member->setUsername($swim_time->getMember());
                            $member->getAllDetails($conn);

                            $iCount++;

                            echo '<tr>';

                            if ($iCount == 1) {
                                echo '<td data-th="Length" rowspan="' . count($swim_times) . '" class="centre">' . $length->getLength() . '</td>';
                            
                                if (is_null($event->getAgeLower()) && is_null($event->getAgeUpper())) {
                                    echo '<td class="none" rowspan="' . count($swim_times) . '" ></td>';
                                } elseif ($event->getAgeLower() == $event->getAgeUpper()) {
                                    echo '<td data-th="Age Group" rowspan="' . count($swim_times) . '" class="centre">' . $event->getAgeLower() . ' years</td>';
                                } elseif ($event->getAgeLower() == null) {
                                    echo '<td data-th="Age Group" rowspan="' . count($swim_times) . '" class="centre">Up to ' . $event->getAgeUpper() . ' years</td>';
                                } elseif ($event->getAgeUpper() == null) {
                                    echo '<td data-th="Age Group" rowspan="' . count($swim_times) . '" class="centre">' . $event->getAgeLower() . ' years and over</td>';
                                } else {
                                    echo '<td data-th="Age Group" rowspan="' . count($swim_times) . '" class="centre">' . $event->getAgeLower() . ' - ' . $event->getAgeUpper() . ' years</td>';
                            }   

                                if ($event->getGender() == 'F') {
                                    echo '<td data-th="Gender" rowspan="' . count($swim_times) . '" class="centre">Female</td>';
                                } elseif ($event->getGender() == 'M') {
                                    echo '<td data-th="Gender" rowspan="' . count($swim_times) . '" class="centre">Male</td>';
                                } else {
                                    echo '<td data-th="Gender" rowspan="' . count($swim_times) . '" class="centre">Mixed</td>';
                                }
                            }                            
                            
                            echo '<td data-th="Swimmer" class="centre">' . $member->getFirstName() . ' ' . $member->getLastName() . '</td>
                            <td data-th="Time" class="centre">' . $swim_time->getTime() . '</td>';

                            if (!is_null($swim_time->getRank())) {
                                $rank = $swim_time->getRank();
                                //Switch rank with Title if required.
                                switch ($rank)
                                {
                                    case -1:
                                        echo '<td data-th="Rank" class="centre">Speeding Ticket</td>';
                                        break;
                                    case 99:
                                        echo '<td data-th="Rank" class="centre">DQ</td>';
                                        break;
                                    case 98:
                                        echo '<td data-th="Rank" class="centre">No Show</td>';
                                        break;
                                    default:
                                        echo '<td data-th="Rank" class="centre">' . $rank . '</td>';
                                        break;
                                }
                            }  else {
                                echo '<td></td>';
                            }

                            if (isset($_SESSION["username"]) && (galaFullAccess($connection,$currentUser,$memberValidation))) {
                                echo '<td class="none class="centre"><a href="' . $domain . 'swim-times/edit.php?galaID=' . $gala->getID() . '&eventID=' . $event->getID() . '&member=' . $member->getUsername() . '">Edit</a></td>';
                            }  

                            echo '</tr>';
                        }

                    }

                    echo '</table>';
                    echo '<a href="#results" class="right">Back to top</a>';
                }

            }

            echo '<div class="row"></div>';

            if (isset($_SESSION['username']) && (galaFullAccess($connection,$currentUser,$memberValidation))) {
                echo linkButton("Add Swim Times", $domain . "swim-times/create.php?galaID=".$gala->getID(), true) . '<br>';
            }

            dbClose($conn);
            ?>
            
            </table>
        </div>    
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>