<?php
    session_start();
    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!venueViewAccess($connection, $currentUser, $memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }


?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Venues | Bucksburn Amatuer Swimming Club</title>    
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
                <li class="current">Venues</li>
            </ul>
            
            <h2>Venues</h2>    
            
            <table class="large-12 medium-12 small-12 columns">     
                <tr>
                    <th>Venue Name</th>
                    <th>Address Line 1</th>
                    <th>City</th>
                    <th>Telephone</th>
                    <?php
                    if(venueFullAccess($connection, $currentUser, $memberValidation)) {
                    echo '<th>View Details</th>';
                    }
                    ?>
                </tr>
            <?php
                require 'obj/venues.obj.php';

                if (isset($_SESSION['delete'])) {
                    echo '<p class="alert-box success radius centre">Venue successfully deleted!</p>';
                    unset($_SESSION['delete']);
                }

                $conn = dbConnect();
                
                $venueItem = new Venues();
                $venueList = $venueItem->listAll($conn);

                if (count($venueList) == 0) {
                    echo '<tr><td class="centre" colspan="6">No Venues were found.</td></tr>';                        
                } else {
                    foreach ($venueList as $venue) {
                        $venueItem->setID($venue["id"]);
                        $venueItem->getAllDetails($conn);            

                        $link = "venues/view.php?id=" . $venueItem->getID();

                        echo '<tr>';
                        echo '<td data-th="Venue">' . $venueItem->getVenue() . '</td>';
                        echo '<td>' . $venueItem->getAddress1() . '</td>';
                        echo '<td>' . $venueItem->getCity() . '</td>';
                        echo '<td>' . $venueItem->getTelephone() . '</td>';
                        if(venueFullAccess($connection, $currentUser, $memberValidation)) {
                            echo '<td><a href="' . $link . '">View Details</a></td>';
                        }
                        echo '</tr>';
                    }
                }

                dbClose($conn);
            ?>
            
            </table>
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
