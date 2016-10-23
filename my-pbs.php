<?php session_start();

    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>My Personal Bests | Members | Bucksburn Amatuer Swimming Club</title>   
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
                <li><a href="members.php" role="link">Members</a></li>
                <li class="current">My Personal Bests</li>
            </ul>
        
            <h2>My Personal Bests</h2>        
          
                <?php
                require 'inc/forms.inc.php';
                require_once 'obj/members.obj.php';
                require 'obj/galas.obj.php';
                require 'obj/gala_events.obj.php';
                require 'obj/strokes.obj.php';
                require 'obj/lengths.obj.php';
                require 'obj/swim_times.obj.php';

                $conn = dbConnect();

                //$member = new Members($_SESSION["username"]);
                $member = new Members("alibai16");
                $member->getAllDetails($conn);
                
                $gala = new Galas();
                $event = new GalaEvents();
                $stroke = new Strokes();
                $length = new Lengths();
                $swim_time = new Swim_Times();

                $strokes = $stroke->listAllStrokesForSwimmer($conn,$member->getUsername());
    
                echo '<h3>' . $member->getFirstName() . ' ' . $member->getLastName() . '</h3>';

                if (count($strokes) == 0) {
                    echo '<p class="centre"><b>There are no swimming times to show</b></p>';
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
                            <th class="centre">Gala</th>
                            <th class="centre">Date</th>  
                            <th class="centre">Time</th>
                            <th class="centre">Rank</th>
                        <tr>';

                    $swim_times = $swim_time->listAllPBsForSwimmerByStrokeAndEvent($conn,$member->getUsername(),$stroke->getID(),$event->getID());
                    
                    foreach ($swim_times as $swim_timesItem) {
                        $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);
                        $swim_time->getAllDetails($conn);

                        $gala->setID($swim_timesItem["galaID"]);
                        $gala->getAllDetails($conn);
                        $event->setID($swim_timesItem["eventID"]);
                        $event->getAllDetails($conn,$gala->getID());
                        $length->setID($event->getLengthID());
                        $length->getAllDetails($conn);

                        echo '<tr>';

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

                        echo '<td data-th="Gala" class="centre">' . $gala->getTitle() . '</td>
                        <td data-th="Date" class="centre">' . date("d/m/Y", strtotime($gala->getDate())) . '</td>';
                           
                        echo '<td data-th="Time" class="centre">' . $swim_time->getTime() . '</td>';
                            
                        if (!is_null($swim_time->getRank())) {
                            echo '<td data-th="Rank" class="centre">' . $swim_time->getRank() . '</td>';
                        }                            
                            
                        echo '</tr>';
                    }

                    echo '</table>';
                    echo '<a href="#results" class="right">Back to top</a>';
                    }
            }
                dbClose($conn);
            ?>
              
        </div> 
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
