<?php 
    session_start(); 

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }


    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/swim_times.obj.php';

    if(!galaFullAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $swim = new Swim_Times($_GET["member"],$_GET["galaID"],$_GET["eventID"]);
        
        if ($swim->delete($connection)) {
            $_SESSION['delete'] = true;
            
            header('Location:' . $domain . 'gala-results/view.php?id=' . $_GET["galaID"]);
            die();
        } else {
            $_SESSION['error'] = true;
        }
        dbClose($connection);
    }    
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Delete | Swim Times | Bucksburn Amatuer Swimming Club</title>   
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
                <li><?php echo '<a href="../gala-results/view.php?id' . $_GET["galaID"] . '"';?> role="link">Swim Times</a></li>
                <li class="current">Delete a Swim Time</li>
            </ul>
            
            <h2>Delete a Swim Time</h2>
                      
            <?php
                require '../inc/forms.inc.php';
                require '../obj/galas.obj.php';
                require '../obj/gala_events.obj.php';
                require_once '../obj/members_roles.obj.php';
                require_once '../obj/members.obj.php';
                require '../obj/lengths.obj.php';
                require '../obj/strokes.obj.php';

                $conn = dbConnect();

                $gala = new Galas($_GET["galaID"]);
                $gala->getAllDetails($conn);
                $event = new GalaEvents($_GET["eventID"]);
                $event->getAllDetails($conn,$gala->getID());
                $member = new Members_Roles();
                $members = new Members($_GET["member"]);
                $members->GetAllDetails($conn);
                $swimTime = new Swim_Times($_GET["member"],$gala->getID(),$event->getID());
                $swimTime->getAllDetails($conn);
                $lengths = new Lengths($event->getLengthID());
                $lengths->getAllDetails($conn);
                $strokes = new Strokes($event->getStrokeID());
                $strokes->getAllDetails($conn);

                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the Swim Time. Please try again.</p>';
                    unset($_SESSION['error']);
                }               
                
                echo formStart(false);

                echo '<div class="panel"><p class="centre middle">Are you sure you want to delete the swim time for <b>' . $members->getFirstName() . ' ' . $members->getLastName() . '</b> in the <b>' . $lengths->getLength() . ' ' . $strokes->getStroke() . '</b> at <b>' . $gala->getTitle() . '</b>?</p></div>';

                echo formEndWithDeleteButton("Delete");

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
