<?php
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/swim_times.obj.php';
    
    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!galaFullAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $swim = new Swim_Times();
        
        if ($swim->isInputValid($_POST["sltGala"],$_POST["sltEvent"],$_POST["sltMember"],$_POST["txtTime"],$_POST["sltRank"])) {
            
            $swim->setMember($_GET["member"]);
            $swim->setGalaID($_GET["galaID"]);
            $swim->setEventID($_GET["eventID"]);            

            if ($swim->delete($connection)) {
                $swim->setMember($_POST["sltMember"]);
                $swim->setGalaID($_POST["sltGala"]);
                $swim->setEventID($_POST["sltEvent"]);
                $swim->setTime($_POST["txtTime"]);
                if (!empty($_POST["sltRank"])) {
                    $swim->setRank($_POST["sltRank"]);
                }                

                if ($swim->create($connection)) {
                    $_SESSION['update'] = true;

                    header('Location:' .$domain . 'gala-results/view.php?id=' . $_GET["galaID"]);
                    die();
                } else {
                    $_SESSION['error'] = true;
                }
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
    <title>Edit | Swim Time | Bucksburn Amateur Swimming Club</title>
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
                <li><a <?php echo 'href="../gala-results/view.php?id=' . $_GET["galaID"] .'"'; ?> role="link">Swim Times</a></li>
                <li class="current">Edit a Swim Time</li>
            </ul>

            <h2>Edit a Swim Time</h2>
            
            <?php
                require '../inc/forms.inc.php';
                require '../obj/galas.obj.php';
                require '../obj/gala_events.obj.php';
                require_once '../obj/members_roles.obj.php';
                require '../obj/squads.obj.php';

                $conn = dbConnect();

                $gala = new Galas($_GET["galaID"]);
                $event = new GalaEvents($_GET["eventID"]);
                $member = new Members_Roles();
                $swimTime = new Swim_Times($_GET["member"],$gala->getID(),$event->getID());
                $swimTime->getAllDetails($conn);

                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">The Swim Time was updated successfully!</p>';
                    unset($_SESSION['update']);
                    unset($_POST);
                }
                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error adding the new Swim Time. Please try again.</p>';
                    unset($_SESSION['error']);
                }
    
                echo formStart();

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltGala"])) {
                        echo comboInputEmptyError(true, "Gala", "sltGala", "Please select a Gala...", "errEmptyGala","Please select a Gala",$gala->listAllGalas($conn));
                    } else {
                        echo comboInputPostback(true, "Gala", "sltGala", $_POST["sltGala"], $gala->listAllGalas($conn));
                    }                    
                } else {
                    echo comboInputSetup(true,"Gala","sltGala",$gala->getID(),$gala->listAllGalas($conn));
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltEvent"])) {
                        echo comboInputEmptyError(true, "Gala Event", "sltEvent", "Please select a Gala Event...", "errEmptyEvent","Please select a Gala Event",$event->listAllEvents($conn, $gala->getID()));
                    } else {
                        echo comboInputPostback(true, "Gala Event", "sltEvent", $_POST["sltEvent"], $event->listAllEvents($conn, $gala->getID()));
                    }                    
                } else {
                    echo comboInputSetup(true,"Gala Event","sltEvent",$event->getID(),$event->listAllEvents($conn, $gala->getID()));
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["sltMember"])) {
                        echo comboInputEmptyError(true, "Member", "sltMember", "Please select a Member...", "errEmptyMember","Please select a Member",$member->listAllSwimmers($conn));
                    } else {
                        echo comboInputPostback(true, "Member", "sltMember", $_POST["sltMember"], $member->listAllSwimmers($conn));
                    }                    
                } else {
                    echo comboInputSetup(true,"Member","sltMember",$swimTime->getMember(),$member->listAllSwimmers($conn));
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtTime"])) {
                        echo textInputEmptyError(true, "Time</b> (e.g. mm:ss.tt)", "txtTime", "errEmptyTime", "Please enter a Time", 8);
                    } else {
                        if ($swim->isTimeValid($_POST["txtTime"])) {
                            echo textInputPostback(true, "Time</b> (e.g. mm:ss.tt)", "txtTime", $_POST["txtTime"], 8);
                        } else {
                            //textInputPostbackError($isRequired, $labelDescription, $labelControlName, $text, $errorControlName, $errorMessage, $maxLength)
                            echo textInputPostbackError(true, "Time</b> (e.g. mm:ss.tt)", "txtTime", "errTime", "errTime", "Please enter a valid Time", 8);
                        }
                    }
                } else {
                    echo textInputSetup(true, "Time</b> (e.g. mm:ss.tt)", "txtTime", $swimTime->getTime(), 8);
                }


            if (isset($_POST["btnSubmit"])) {
                echo comboInputPostback(false, "Swim Rank", "sltRank", $_POST["sltRank"], $event->listRanks());

             if (is_null($swimTime->getRank())) {
                echo comboInputBlank(false,"Swim Rank","sltRank", "Please select a Rank...",$event->listRanks());
             }
            }
            else {
                echo comboInputSetup(false,"Swim Rank","sltRank",$swimTime->getRank(),$event->listRanks());
            }

                echo formEndWithButton("Save Changes", $domain . "swim-times/delete.php?galaID=" . $gala->getID() . "&eventID=" . $event->getID() . "&member=" . $swimTime->getMember());

            ?>
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
