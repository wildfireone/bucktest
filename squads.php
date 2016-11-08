<?php
    session_start();

    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';

    if (!isset($_SESSION['username'])) {
        header('location: ' . $domain . '/message.php?id=badaccess');
        die();
    }

    if(!squadViewAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Squads | Bucksburn Amateur Swimming Club</title>
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>   
    <link href="css/site.css" rel="stylesheet"/>
</head>

<body>  
    <?php include 'inc/header.inc.php'; ?>   
    <br>
    <div class="row" id="content">
        <div class="row-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="index.php" role="link">Home</a></li>
                <li class="current">Squads</li>
            </ul>
            
            <h2>Squads</h2>    

            <?php
            if (isset($_SESSION['delete'])) {
                echo '<p class="alert-box success radius centre">Squad deleted successfully!</p>';
                unset($_SESSION['delete']);
            }
            ?>
            
            <table class="large-12 medium-12 small-12 columns">     
                <tr>
                    <th>Squad Name</th>
                    <th>Squad Description</th>
                    <th>Coach</th>
                    <th>Number of Members</th>
                    <?php
                    if(squadFullAccess($connection,$currentUser,$memberValidation))
                    {
                        echo   '<th>View Details</th>';
                    }
                    ?>
                </tr>
            <?php
                require 'obj/squads.obj.php';
                require_once 'obj/members.obj.php';

                $conn = dbConnect();
                
                $squadItem = new Squads();
                $member = new Members();
                
                if (!empty($_GET["txtSquad"])) {
                    $pSquad = "%" . strtolower($_GET["txtSquad"]) . "%";
                } else {
                    $pSquad = "";
                }
                if (!empty($_GET["txtDescription"])) {
                    $pDescription = "%" . strtolower($_GET["txtDescription"]) . "%";
                } else {
                    $pDescription = "";
                }
                if (!empty($_GET["sltCoach"])) {
                    $pCoach = "%" . strtolower($_GET["sltCoach"]) . "%";
                } else {
                    $pCoach = "";
                }
                
                $squadsList = $squadItem->listAllSquadsWithParam($conn, $pSquad, $pDescription, $pCoach);
                
                foreach ($squadsList as $squad) {
                    $squadItem->setID($squad["id"]);
                    $squadItem->getAllDetails($conn);            
                    $member->setUsername($squadItem->getCoach());

                    //set hyperlink to point to member search with squadID parameter
                    $squadCoachLink = "members/view.php?u=" . $squadItem->getCoach();
                    $squadCountLink = "members.php?squadID=" . $squadItem->getID();
                    $squadViewLink = "squads/view.php?id=" . $squadItem->getID();

                    echo "<tr>";
                    //If user has full access then display links
                    if(squadFullAccess($connection,$currentUser,$memberValidation))
                    {
                        echo '<td data-th="Squad">' . $squadItem->getSquad() . '</td>';
                        echo '<td data-th="Description">' . $squadItem->getDescription() . '</td>';
                        echo '<td data-th="Coach"><a href="'.$squadCoachLink.'">' . $member->getFullNameByUsername($conn) . '</a></td>';
                        echo '<td data-th="No. of Members"><a href="'.$squadCountLink.'">' . $squad["COUNT(*)"] . '</a></td>';
                        echo '<td class="none"><a href="' . $squadViewLink . '">View Details</a></td>';
                    }
                    else //otherwise display data only
                    {
                        echo '<td data-th="Squad">' . $squadItem->getSquad() . '</td>';
                        echo '<td data-th="Description">' . $squadItem->getDescription() . '</td>';
                        echo '<td data-th="Coach"> '. $member->getFullNameByUsername($conn) . '/td>';
                        echo '<td data-th="No. of Members">'.$squad["COUNT(*)"].'</td>';
                    }

                    echo "</tr>";
                }

                dbClose($conn);
            ?>
            
            </table>
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
