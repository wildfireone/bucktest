<?php
    session_start();
    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Club Records | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
</head>

<body>   
    <?php include '../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
        <ul class="breadcrumbs">
            <li><a href="../index.php" role="link">Home</a></li>
            <li><a href="../pages/view.php?id=1" role="link">About</a></li>
            <li class="current">Club Records</li>
        </ul>
            
        <h2>Club Records</h2>
                    
            <p>Below is a list of all the club records that have been achieved by the members of Bucksburn Amateur Swimming Club.</p>
            
            
            <?php         
                echo '<table class="large-12 medium-12 small-12 columns">
                    <tr>
                        <th>Stroke</th>
                        <th>Distance</th>
                        <th>Age Group</th>
                        <th>Gender</th>
                        <th>Swimmer</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>';


                require '../obj/club_records.obj.php';
                require '../obj/strokes.obj.php';
                require '../obj/lengths.obj.php';
                require_once '../obj/members.obj.php';

                $conn = dbConnect();

                $clubRecordItem = New Club_Records();
                $clubRecords = $clubRecordItem->listAllClubRecords($conn);

                $stroke = New Strokes();
                $length = New Lengths();
                $member = New Members();

                foreach ($clubRecords as $clubRecord) {
                    $clubRecordItem->setStrokeID($clubRecord["strokeID"]);
                    $clubRecordItem->setLengthID($clubRecord["lengthID"]);
                    $clubRecordItem->setAgeLower($clubRecord["ageLower"]);
                    $clubRecordItem->setAgeUpper($clubRecord["ageUpper"]);
                    $clubRecordItem->setEventType($clubRecord["eventType"]);
                    $clubRecordItem->getAllDetails($conn);
                    
                    $stroke->setID($clubRecordItem->getStrokeID());
                    $stroke->getAllDetails($conn);
                    $length->setID($clubRecordItem->getLengthID());
                    $length->getAllDetails($conn);
                    $member->setUsername($clubRecordItem->getMaleMember());
                    echo '<tr>
                        <td data-th="Stroke">' . $stroke->getStroke() . '</td>
                        <td data-th="Length">' . $length->getLength() . '</td>
                        <td data-th="Age Group">' . $clubRecordItem->getAgeLower() . ' - ' . $clubRecordItem->getAgeUpper() . '</td>
                        <td data-th="Gender">Male</td>
                        <td data-th="Swimmer">' . $member->getFullNameByUsername($conn) . '</td>
                        <td data-th="Date">' . $clubRecordItem->getMaleDate() . '</td>
                        <td data-th="Time">' . $clubRecordItem->getMaleTime() . '</td>
                    </tr>';

                    $member->setUsername($clubRecordItem->getFemaleMember());

                    echo '<tr>
                        <td data-th="Stroke">' . $stroke->getStroke() . '</td>
                        <td data-th="Length">' . $length->getLength() . '</td>
                        <td data-th="Age Group">' . $clubRecordItem->getAgeLower() . ' - ' . $clubRecordItem->getAgeUpper() . '</td>
                        <td data-th="Gender">Female</td>
                        <td data-th="Swimmer">' . $member->getFullNameByUsername($conn) . '</td>
                        <td data-th="Date"></td>
                        <td data-th="Time">' . $clubRecordItem->getFemaleTime() . '</td>
                    </tr>';
                }

                echo '</table>';
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
