<?php 
    session_start(); 

    // Check for a parameter before we send the header
    if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {        
            header( 'Location:' . $domain . '404.php' );
            die();
    }
    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!venueFullAccess($connection, $currentUser, $memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en-GB">

<head>    
    <?php include '../inc/meta.inc.php'; ?>
    <title>View | Venues | Bucksburn Amateur Swimming Club</title>  
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>     
    <link href="../css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include '../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="row-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="../index.php" role="link">Home</a></li>
                <li><a href="../venues.php" role="link">Venues</a></li>
                <li class="current">View a Venue</li>
            </ul>
        
            <h2>View a Venue</h2>    
            
             <?php
                    if (isset($_SESSION['update'])) {
                        echo '<p class="alert-box success radius centre">Changes saved!</p>';
                        unset($_SESSION['update']);
                    }

                    if (isset($_SESSION['create'])) {
                        echo '<p class="alert-box success radius centre">Venue created successfully!</p>';
                        unset($_SESSION['create']);
                    }
            ?>
                            
            <?php
                    require '../inc/forms.inc.php';
                    require '../obj/venues.obj.php';

                    $conn = dbConnect();

                    $venue = new Venues($_GET["id"]);                    
                    $venue->getAllDetails($conn);  

                    echo '<div class="large-9 large-centered medium-12 small-12 columns"><fieldset><legend>Venue Details</legend>';
                    echo formStart();

                    echo textInputSetup(true, "Venue ID", "txtID", $venue->getID(), 3, true);
                    echo textInputSetup(true, "Venue Name", "txtVenue", $venue->getVenue(), 250, true);
                    echo textInputSetup(true, "Address Line 1", "txtAddress1", $venue->getAddress1(), 50, true);
                    echo textInputSetup(false, "Address Line 2", "txtAddress2", $venue->getAddress2(), 50, true);
                    echo textInputSetup(true, "City", "txtCity", $venue->getCity(), 50, true);
                    echo textInputSetup(false, "County", "txtCounty", $venue->getCounty(), 50, true);
                    echo textInputSetup(true, "Postcode", "txtPostcode", $venue->getPostcode(), 50, true);
                    echo telInputSetup(false, "Telephone", "txtTelephone", $venue->getTelephone(), 12, true);
                    echo emailInputSetup(false, "Email", "txtEmail", $venue->getEmail(), 250, true);
                    echo textInputSetup(false, "Website", "txtWebsite", $venue->getWebsite(), 250, true);                    

                    echo linkButton("Edit this Venue", "edit.php?id=".$venue->getID());

                    echo formEnd();      
                    echo '</fieldset></div>';

                    dbClose($conn);
                ?> 
                    
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
