<?php session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';


if (!isset($_SESSION['username'])) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}


// Check for a parameter before we send the header
if (is_null($_GET["squadID"]) || !is_numeric($_GET["squadID"])) {
    header('Location:' . $domain . '404.php');
    die();
}

if (!squadFullAccess($connection, $currentUser, $memberValidation)) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}

require '../obj/squads.obj.php';
$conn = dbConnect();


$squadItem = new Squads($_GET["squadID"]);
$squadItem->getAllDetails($conn);

?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Squad Timesheets | <?php echo $squadItem->getSquad() ?> | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
</head>

<body>
<?php include '../inc/header.inc.php'; ?>
<br>


<?php
require '../inc/forms.inc.php';
require_once '../obj/members.obj.php';
require '../obj/galas.obj.php';
require '../obj/gala_events.obj.php';
require '../obj/strokes.obj.php';
require '../obj/lengths.obj.php';
require '../obj/swim_times.obj.php';
require_once '../obj/members_roles.obj.php';

$conn = dbConnect();


$squadItem = new Squads($_GET["squadID"]);
$squadItem->getAllDetails($conn);

$gala = new Galas();
$event = new GalaEvents();
$stroke = new Strokes();
$length = new Lengths();
$swim_time = new Swim_Times();
$memberItem = new Members();

$membersList = $memberItem->listAllSquadMembers($conn, $_GET["squadID"]);


////                  1	Butterfly
//                    2	Backstroke
//                    3	Breaststroke
//                    4	Freestyle
//                    5	Individual Medley


$freeStyle = array();
$butterFly = array();
$backstroke = array();
$breaststroke = array();
$im = array();

//Array structure:
// $array[username][acc 0/1][length (1-7)][swim_time object]


?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <!--DWLayoutTable-->
    <tr>
        <td colspan="2" valign="top">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                    <td align="center" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                            <tr>
                                <th align="left" width="60%">&nbsp;BUCKSBURN AMATEUR SWIMMING CLUB - SQUAD PB
                                    TIMESHEET (SHORT TIMES ONLY)
                                </th>
                                <th align="right" width="40%"><?php echo $squadItem->getSquad() ?></th>
                            </tr>
                        </table>
                        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="textsmall">
                            <tr class="titletext">
                                <th>&nbsp;Date: <?php echo gmdate("d/m/Y") ?></th>
                                <th>&nbsp;</th>
                                <th colspan="12">FRONTCRAWL</th>
                                <th colspan="6">I.M.</th>
                            </tr>
                            <tr class="titletext">
                                <th>Name</th>
                                <th>Type</th>
                                <th>25</th>
                                <th>Date</th>
                                <th>50</th>
                                <th>Date</th>
                                <th>100</th>
                                <th>Date</th>
                                <th>200</th>
                                <th>Date</th>
                                <th>400</th>
                                <th>Date</th>
                                <th>800</th>

                                <th>Date</th>
                                <th>100</th>
                                <th>Date</th>
                                <th>200</th>
                                <th>Date</th>
                                <th>400</th>
                                <th>Date</th>
                            </tr>

                            <?php


                            foreach ($membersList as $member) {

                                $memberItem->setUsername($member["username"]);
                                $memberItem->getAllDetails($conn);

                                echo '<tr class="">
                                <td nowrap>' . $memberItem->getFullNameByUsername($conn) . '</td>
                                <td nowrap>&nbsp;Non-Acc-time&nbsp;</td>';

                                // Loop Non-Accredited times

                                //Freestyle (Front Crawl)
                                for ($i = 1; $i <= 7; $i++) {
                                    $freeStyle[$memberItem->getUsername()][0][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 4, $i, null);

                                    $swim_times = $freeStyle[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                //Individual Medley
                                for ($i = 3; $i < 5; $i++) {
                                    $im[$memberItem->getUsername()][0][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 5, $i, null);

                                    $swim_times = $im[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {

                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);

                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                echo ' </tr>
                                       <tr class="accredited">
                                       <td nowrap>&nbsp;&nbsp;</td>
                                       <td nowrap>&nbsp;Acc-time&nbsp;</td>';

                                // Loop Accredited times

                                //Freestyle (FrontCrawl)
                                for ($i = 1; $i <= 7; $i++) {

                                    $freeStyle[$memberItem->getUsername()][1][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 4, $i, 1);

                                    $swim_times = $freeStyle[$memberItem->getUsername()][1][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            //var_dump($swim_timesItem);
                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                //Individual Medley
                                for ($i = 3; $i < 5; $i++) {

                                    $im[$memberItem->getUsername()][1][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 5, $i, 1);

                                    $swim_times = $im[$memberItem->getUsername()][1][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {

                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);

                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }
                            }
                            ?>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="4"
                               style="page-break-before: always">
                            <tr>
                                <th align="left" width="60%">&nbsp;BUCKSBURN AMATEUR SWIMMING CLUB -SQUAD PB TIMESHEET
                                    (Page 2) (SHORT TIMES ONLY)
                                </th>
                                <th align="right" width="40%"><?php echo $squadItem->getSquad() ?>&nbsp;&nbsp;&nbsp;
                                </th>
                            </tr>
                        </table>
                        <table width="100%" border="1" cellspacing="0" cellspacing="0" class="textsmall">
                            <tr class="titletext">
                                <th>&nbsp;Date: <?php echo gmdate("d/m/Y") ?></th>
                                <th>&nbsp;</th>
                                <th colspan="8" align="center">BUTTERFLY</th>
                                <th colspan="6" align="center">
                                    BACK
                                    CRAWL
                                </th>
                                <th colspan="6" align="center">
                                    BREAST
                                    STROKE
                                </th>
                            </tr>
                            <tr class="titletext">
                                <th>Name</th>
                                <th>Type</th>
                                <th>25</th>
                                <th>Date</th>
                                <th>50</th>
                                <th>Date</th>
                                <th>100</th>
                                <th>Date</th>
                                <th>200</th>
                                <th>Date</th>
                                <th>50</th>
                                <th>Date</th>
                                <th>100</th>
                                <th>Date</th>
                                <th>200</th>
                                <th>Date</th>
                                <th>50</th>
                                <th>Date</th>
                                <th>100</th>
                                <th>Date</th>
                                <th>200</th>
                                <th>Date</th>
                            </tr>
                            <?php
                            foreach ($membersList as $member) {

                                $memberItem->setUsername($member["username"]);
                                $memberItem->getAllDetails($conn);

                                echo '<tr class="">
                                <td nowrap>' . $memberItem->getFullNameByUsername($conn) . '</td>
                                <td nowrap>&nbsp;Non-Acc-time&nbsp;</td>';

                                // Loop Non-Accredited times

                                //Butterfly
                                for ($i = 1; $i <= 4; $i++) {

                                    $butterFly[$memberItem->getUsername()][0][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 1, $i, null);

                                    $swim_times = $butterFly[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            //var_dump($swim_timesItem);
                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                //BackCrawl
                                for ($i = 1; $i <= 3; $i++) {

                                    $backstroke[$memberItem->getUsername()][0][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 2, $i, null);

                                    $swim_times = $backstroke[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            //var_dump($swim_timesItem);
                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                //BreastStroke
                                for ($i = 1; $i <= 3; $i++) {
                                    $breaststroke[$memberItem->getUsername()][0][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 3, $i, null);

                                    $swim_times = $breaststroke[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            //var_dump($swim_timesItem);
                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                echo ' </tr>
                                       <tr class="accredited">
                                       <td nowrap>&nbsp;&nbsp;</td>
                                       <td nowrap>&nbsp;Acc-time&nbsp;</td>';

                                // Loop Accredited times
                                for ($i = 1; $i <= 4; $i++) {
                                    $butterFly[$memberItem->getUsername()][1][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 1, $i, 1);

                                    $swim_times = $butterFly[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            //var_dump($swim_timesItem);
                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                //BackCrawl
                                for ($i = 1; $i <= 3; $i++) {
                                    $backstroke[$memberItem->getUsername()][1][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 2, $i, 1);

                                    $swim_times = $backstroke[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            //var_dump($swim_timesItem);
                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }

                                //BreastStroke
                                for ($i = 1; $i <= 3; $i++) {
                                    $breaststroke[$memberItem->getUsername()][1][$i] = $swim_time->listAllPBsForSwimmerByLengthAcc($conn, $memberItem->getUsername(), 3, 1, 1);

                                    $swim_times = $breaststroke[$memberItem->getUsername()][0][$i];
                                    if (!empty($swim_times)) {
                                        foreach ($swim_times as $swim_timesItem) {
                                            $swim_time->setPKs($swim_timesItem["member"], $swim_timesItem["galaID"], $swim_timesItem["eventID"]);

                                            $swim_time->getAllDetails($conn);
                                            //var_dump($swim_timesItem);
                                            //Get data
                                            $gala->setID($swim_timesItem["galaID"]);
                                            $gala->getAllDetails($conn);

                                            //Display it in table cells
                                            echo ' <td>' . $swim_time->getTime() . '</td>';
                                            echo ' <td>' . $gala->getDate() . '</td>';
                                        }
                                    } else {
                                        echo '<td>&nbsp;-&nbsp;</td> 
                                              <td>&nbsp;-&nbsp;</td>';
                                    }
                                }
                            }
                            ?>
                        </table>
            </table>
            <!--DWLayoutEmptyCell-->
            &nbsp;
        </td>
    </tr>
</table>

<?php include '../inc/footer.inc.php'; ?>
</body>

</html>
