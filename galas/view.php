<?php 
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
    <title>View | Gala | Bucksburn Amateur Swimming Club</title>
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
                <li class="current">View an Upcoming Gala</li>
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
                
                $gala = new Galas($_GET["id"]);
                $gala->getAllDetails($conn); 
                $venue->setID($gala->getVenueID());
                $venue->getAllDetails($conn);

                $strokes = $event->listStrokesForGala($conn,$gala->getID());

                if (isset($_SESSION['create'])) {
                    echo '<p class="alert-box success radius centre">Gala added successfully!</p>';
                    unset($_SESSION['create']);
                }
                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">Changes saved!</p>';
                    unset($_SESSION['update']);
                }

                echo '<h2 id="results">' . $gala->getTitle() . '</h2>';
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
                echo '<br>';
                echo '<b>Course Type</b>: ';
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

                echo '<h5 class="h3 capitalise">Other Details</h5>';
                echo '<p>';
                if (!empty($gala->getDescription())) {echo '<b>Organiser</b>: ' . $gala->getOrganiser() . '<br/>'; }
                if (!empty($gala->getWarmUpTime())) {echo '<b>Warmup Time</b>: ' . $gala->getWarmUpTime() . '<br/>'; }
                if (!($gala->getFees() == 0)) {echo '<b>Fees</b>: £' . $gala->getFees() . '<br/>'; }
                if (!empty($gala->getCutOffDate())) {echo '<b>Cut Off Date</b>: ' . date("d/m/Y", strtotime($gala->getCutOffDate())) . '<br/>'; }
                if (!empty($gala->getNotes())) { echo '<b>Notes</b>: ' . $gala->getNotes(); }
                echo '</p>';

                echo '<p class="centre"><b>Can all swimmers please confirm their attendance by ' . date("l jS F Y", strtotime($gala->getCutOffDate())) . '</b></p>';

                echo '<h3>Gala Events</h3>';

                if (!$gala->doEventsExist($conn)) {
                    echo '<p class="centre"><b>Events for this gala are still to be posted. Please check back soon.</b></p>';
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
                            <th class="centre">Length</th>
                            <th class="centre">Age Group</th>
                            <th class="centre">Gender</th>
                        <tr>';

                    $events = $event->listAllGalaEventsByStroke($conn,$gala->getID(),$stroke->getID());
                
                    foreach ($events as $eventItem) {
                        $event->setID($eventItem["id"]);
                        $event->getAllDetails($conn,$gala->getID());
                        $length->setID($event->getLengthID());
                        $length->getAllDetails($conn);

                        echo '<tr>';
                        echo '<td data-th="Length" class="centre">' . $length->getLength() . '</td>';
                        
                        if (is_null($event->getAgeLower()) && is_null($event->getAgeUpper())) {
                                echo '<td class="none" ></td>';
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

                        echo '</tr>';
                    }

                    echo '</table>';
                    echo '<a href="#results" class="right">Back to top</a>';
                }
                }

                echo '<div class="row">';
                echo '<div class="large-4 medium-4 small-12 columns left">';
                if (isset($_SESSION['username']) && (galaFullAccess($connection,$currentUser,$memberValidation))) {
                    echo linkButton("Edit Gala", $domain . "galas/edit.php?id=".$gala->getID(), true) . '<br>';
                }
                echo '</div><div class="large-4 medium-4 small-12 columns left">';
                if (isset($_SESSION['username']) && (galaFullAccess($connection,$currentUser,$memberValidation))) {
                    echo linkButton("Add or Edit Gala Events", $domain . "gala-events.php?id=".$gala->getID(), true) . '<br>';
                }

                echo '</div><div class="large-4 medium-4 small-12 columns left">';
                if (isset($_SESSION['username']) && (galaFullAccess($connection,$currentUser,$memberValidation)) && $gala->doEventsExist($conn)) {
                    echo linkButton("Add Swim Times", $domain . "swim-times/create.php?galaID=".$gala->getID(), true) . '<br>';
                }
                echo '</div></div>';

                dbClose($conn);
                ?>
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
