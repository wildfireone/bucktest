<?php
    session_start();
    require 'inc/connection.inc.php';

    if (!isset($_SESSION['username'])) {
        header('location: ' . $domain . '/message.php?id=badaccess');
        die();
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Gala Events | Bucksburn Amatuer Swimming Club</title>  
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>     
    <link href="css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include 'inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
            
            <ul class="breadcrumbs">
                <li><a href="index.php" role="link">Home</a></li>
                <li class="current">Gala Events</li>
            </ul>

            <?php
                require 'inc/forms.inc.php';
                require 'obj/venues.obj.php';
                require 'obj/members.obj.php';
                require 'obj/galas.obj.php';
                require 'obj/gala_events.obj.php';
                require 'obj/strokes.obj.php';
                require 'obj/lengths.obj.php';
                require 'obj/swim_times.obj.php';

                if (isset($_SESSION['delete'])) {
                    echo '<p class="alert-box success radius centre">Gala Event successfully deleted!</p>';
                    unset($_SESSION['delete']);
                }
                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">Gala Event successfully updated!</p>';
                    unset($_SESSION['update']);
                }
                if (isset($_SESSION['create'])) {
                    echo '<p class="alert-box success radius centre">Gala Event successfully added!</p>';
                    unset($_SESSION['create']);
                }

                $conn = dbConnect();
                
                $venue = new Venues();
                $member = new Members();
                $event = new GalaEvents();
                $stroke = new Strokes();
                $length = new Lengths();
                $swim_time = new Swim_Times();
                
                $gala = new Galas($_GET["id"]);
                $gala->getAllDetails($conn); 
                $venue->setID($gala->getVenueID());
                $venue->getAllDetails($conn);

                $strokes = $event->listStrokesForGala($conn,$gala->getID());
                $events = $event->listAllGalaEvents($conn,$gala->getID());

                echo '<h2 id="results">' . $gala->getTitle() . ' Events</h2>';
                echo '<h3 class="h4 capitalise">' . $venue->getVenue() . '</h3>';
                echo '<h4 class="h5 italic">' . date("l jS F Y", strtotime($gala->getDate())) . '</h4>';

                echo '<p><b>Accredited</b>: ';
                if ($gala->getIsAccredited()) {
                    echo 'Yes';
                } else {
                    echo 'No';
                }
                echo '<br>';
                if ($gala->getIsLongCourse()) {
                    echo '<b>Type</b>: Long Course';
                } else {
                    echo '<b>Type</b>: Short Course';
                }
                echo '</p>';

                if (count($events) == 0) {
                    echo '<p class="centre"><b>No gala events currently exist for this swimming gala.</b></p>';
                } else {



                echo '<span><b>Skip to:</b></span><ol>';
                foreach ($strokes as $strokeItem) {
                    $stroke->setID($strokeItem["strokeID"]);
                    $stroke->getAllDetails($conn);
                    echo '<li><a href="#' . strtolower($stroke->getStroke()) . '">' . $stroke->getStroke() . '</a></li>';

                }
                echo '</ol>';

                foreach ($strokes as $strokeItem) {
                    $stroke->setID($strokeItem["strokeID"]);
                    $stroke->getAllDetails($conn);

                    echo '<h5 class="h3 capitalise centre clearfix" id="' . strtolower($stroke->getStroke()) . '">' . $stroke->getStroke() . '</h5>';

                    echo '<table class="large-12 medium-12 small-12 columns">
                        <tr>
                            <th class="centre">ID</th>
                            <th class="centre">Length</th>
                            <th class="centre">Age Group</th>
                            <th class="centre">Gender</th>
                            <th class="centre"></th>
                        <tr>';

                    $events = $event->listAllGalaEventsByStroke($conn,$gala->getID(),$stroke->getID());
                
                    foreach ($events as $eventItem) {
                        $event->setID($eventItem["id"]);
                        $event->getAllDetails($conn,$gala->getID());
                        $length->setID($event->getLengthID());
                        $length->getAllDetails($conn);

                        echo '<tr>';
                        echo '<td data-th="ID" class="centre">' . $event->getID() . '</td>';
                        echo '<td data-th="Length" class="centre">' . $length->getLength() . '</td>';
                        
                        if (is_null($event->getAgeLower()) && is_null($event->getAgeUpper())) {
                                echo '<td class="none"></td>';
                            } elseif ($event->getAgeLower() == $event->getAgeUpper()) {
                                echo '<td data-th="Age Group" class="centre">' . $event->getAgeLower() . ' years</td>';
                            } elseif ($event->getAgeLower() == null) {
                                echo '<td data-th="Age Group" class="centre">Up to ' . $event->getAgeUpper() . ' years</td>';
                            } elseif ($event->getAgeUpper() == null) {
                                echo '<td data-th="Age Group" class="centre">' . $event->getAgeLower() . ' years and over</td>';
                            } else {
                                echo '<td data-th="Age Group" class="centre">' . $event->getAgeLower() . ' - ' . $event->getAgeUpper() . ' years</td>';
                        }   

                        if ($event->getGender() == 'F') {
                            echo '<td data-th="Gender" class="centre">Female</td>';
                        } elseif ($event->getGender() == 'M') {
                            echo '<td data-th="Gender" class="centre">Male</td>';
                        } else {
                            echo '<td data-th="Gender" class="centre">Mixed</td>';
                        }

                        echo '<td class="none centre"><a href="gala-events/edit.php?id=' . $event->getID() . '&galaID=' . $gala->getID() . '">Edit this Event</a></td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '<a href="#results" class="right">Back to top</a>';
                }
                }
                if (isset($_SESSION['username'])) {
                    echo '<div class="large-12 medium-12 small-12 columns">';
                    echo linkButton("Add Gala Events", "gala-events/create.php?id=" . $gala->getID(), true) . '<br>';
                    echo '</div>';
                }
                

                ?>
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
