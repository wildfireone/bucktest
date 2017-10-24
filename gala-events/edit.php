<?php
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/gala_events.obj.php';
    
    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }


    if (is_null($_GET["id"]) || is_null($_GET["galaID"])) {        
        header( 'Location:' . $domain . '404.php' );
        exit;
    } else {
        $gala = new GalaEvents($_GET["id"], $_GET["galaID"]);
        if (!$gala->doesExist($connection)) {
            header( 'Location:' . $domain . '404.php' );
            exit;
        }

        if(!galaFullAccess($connection,$currentUser,$memberValidation))
        {
            header( 'Location:' . $domain . 'message.php?id=badaccess' );
            exit;
        }
    }

    if (isset($_POST["btnSubmit"])) {
        $gala = new GalaEvents($_GET["id"], $_GET["galaID"]);
        if ($gala->isInputValid('zzz',$_GET["galaID"], $_POST["sltStroke"], $_POST["sltLength"], $_POST["sltGender"], $_POST["txtAgeLower"], $_POST["txtAgeUpper"], $_POST['txtSubGroup'], $connection)) {
            $gala->setStrokeID($_POST['sltStroke']);
            $gala->setLengthID($_POST['sltLength']);
            $gala->setGender($_POST['sltGender']);
            $gala->setAgeLower($_POST['txtAgeLower']);
            $gala->setAgeUpper($_POST['txtAgeUpper']);
            $gala->setSubGroup($_POST['txtSubGroup']);
                
            if ($gala->update($connection)) {
                $_SESSION['update'] = true;

                header( 'Location:' . $domain . 'gala-events.php?id=' . $_GET["galaID"]);
            exit;
            } else {
                $_SESSION['error'] = true;
            }
        } else {
            $_SESSION['invalid'] = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Edit | Gala Events | Bucksburn Amateur Swimming Club</title>
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
                <li><?php echo '<a href="../gala-events.php?id=' . $_GET["galaID"] . '" role="link">'; ?>Gala Events</a></li>
                <li class="current">Edit a Gala Event</li>
            </ul>
    
            <h2>Edit a Gala Event</h2>
            
            <?php
                require '../inc/forms.inc.php';
                require '../obj/galas.obj.php';
                require '../obj/venues.obj.php';
                require '../obj/strokes.obj.php';
                require '../obj/lengths.obj.php';
                require_once '../obj/members.obj.php';

                $conn = dbConnect();
                
                $strokes = new Strokes();
                $lengths = new Lengths();
                $member = new Members();

                $event = new GalaEvents($_GET["id"], $_GET["galaID"]);
                $event->getAllDetails($conn,$_GET["galaID"]);

                $galas = new Galas($_GET["galaID"]);
                $galas->getAllDetails($conn);

                $venues = new Venues($galas->getVenueID());
                $venues->getAllDetails($conn);

                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">Changes saved successfully!</p>';
                    unset($_SESSION['update']);
                    unset($_POST);
                }
                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error adding the new Gala Event. Please try again.</p>';
                    unset($_SESSION['error']);
                }
    
                echo formStart();

                echo '<div class="large-6 medium-6 small-12 left"><fieldset><legend>Gala Event Details</legend>';


                echo textInputSetup(true,"ID","txtID",$event->getID(),16,true);


                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if (empty($_POST["sltStroke"])) {
                        echo comboInputEmptyError(true, "Stroke", "sltStroke", "Please select a Stroke...", "errEmptyStroke","Please select a Stroke",$strokes->listAllStrokes($conn));
                    } else {
                        echo comboInputPostback(true, "Stroke", "sltStroke", $_POST["sltStroke"], $strokes->listAllStrokes($conn));
                    }                    
                } else {
                    echo comboInputSetup(true,"Stroke","sltStroke",$event->getStrokeID(),$strokes->listAllStrokes($conn));
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if (empty($_POST["sltLength"])) {
                        echo comboInputEmptyError(true, "Length", "sltLength", "Please select a Length...", "Length","Please select a Length",$lengths->listAllLengths($conn));
                    } else {
                        echo comboInputPostback(true, "Length", "sltLength", $_POST["sltLength"], $lengths->listAllLengths($conn));
                    }                    
                } else {
                    echo comboInputSetup(true,"Length","sltLength",$event->getLengthID(),$lengths->listAllLengths($conn));
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if (empty($_POST["sltGender"])) {
                        echo comboInputEmptyError(true, "Gender", "sltGender", "Please select a Gender...", "Gender","Please select a Gender",$member->listGenders(true));
                    } else {
                        echo comboInputPostback(true,"Gender","sltGender",$_POST["sltGender"], $member->listGenders(true));
                    }                    
                } else {
                    echo comboInputSetup(true,"Gender","sltGender",$event->getGender(), $member->listGenders(true));
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if ($event->isAgeLowerValid($_POST["txtAgeLower"])) {
                        echo numInputPostback(false, "Lower Age Limit</b> (leave blank if none exists)", "txtAgeLower", $_POST["txtAgeLower"], 8,15);
                    } else {
                    echo numInputPostback(false, "Lower Age Limit</b> (leave blank if none exists)", "txtAgeLower", $_POST["txtAgeLower"], 8,15);
                    }
                } else {
                    echo numInputSetup(false,"Lower Age Limit</b> (leave blank if none exists)","txtAgeLower",$event->getAgeLower(),8,15);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if ($event->isAgeUpperValid($_POST["txtAgeUpper"])) {
                        echo numInputPostback(false, "Upper Age Limit</b> (leave blank if none exists)", "txtAgeUpper", $_POST["txtAgeUpper"], 8,15);
                    } else {
                        echo numInputPostbackError(false, "Upper Age Limit</b> (leave blank if none exists)", "txtAgeUpper", $_POST["txtAgeUpper"], 8,15,"errAgeLower","Please enter a valid number between 8 and 15");
                    }
                } else {
                    echo numInputSetup(false,"Upper Age Limit</b> (leave blank if none exists)","txtAgeUpper",$event->getAgeUpper(),8,15);
                }         

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    echo textInputPostback(false, "Sub Group</b> (leave blank if none exists)", "txtSubGroup", $_POST["txtSubGroup"], 1);
                } else {
                    echo textInputSetup(false,"Sub Group</b> (leave blank if none exists)","txtSubGroup", $event->getSubGroup(), 1);
                }              

                echo '</fieldset></div><div class="large-6 medium-6 small-12 right"><fieldset><legend>Gala Details</legend>';

                if (isset($_POST["sltGala"])) {
                    $galas->setID($_POST['sltGala']);
                    $galas->getAllDetails($conn);
                    $venues->setID($galas->getVenueID());
                    $venues->getAllDetails($conn);
                        
                    echo linkButton("Add a new Gala", '../galas/create.php',true);
                    
                    echo comboInputSetup(true,"Gala","sltGala",$_POST["sltGala"],$galas->listAllgalas($conn),true);
                    echo textInputSetup(false,"ID","txtGalaID",$galas->getID(),8,true);
                    echo textInputSetup(false,"Title","txtTitle",$galas->getTitle(),250,true);
                    echo textInputSetup(false,"Date","txtDate",date("d/m/Y", strtotime($galas->getDate())),50,true);
                    echo textInputSetup(false,"Venue","txtVenue",$venues->getVenue(),250,true);

                    echo '<div class="large-6 medium-12 small-12 columns"><a href="../galas/edit.php?id=' . $galas->getID() . '" class="medium radius button middle centre h6 capitalise">Edit this Gala</a></div>';
                } else {                    
                    echo linkButton("Add a new Gala", '../galas/create.php',true);
                    
                    echo comboInputSetup(true,"Gala","sltGala",$event->getGalaID(),$galas->listAllgalas($conn),true); 
                    echo textInputSetup(false,"ID","txtGalaID",$galas->getID(),8,true);
                    echo textInputSetup(false,"Title","txtTitle",$galas->getTitle(),250,true);
                    echo textInputSetup(false,"Date","txtDate",date("d/m/Y", strtotime($galas->getDate())),50,true);
                    echo textInputSetup(false,"Venue","txtVenue",$venues->getVenue(),250,true);
                }
                
                echo '</fieldset></div>';

                echo formEndWithButton("Save Changes","delete.php?id=" . $event->getID() . "&galaID=" . $event->getGalaID());

            ?>

        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
