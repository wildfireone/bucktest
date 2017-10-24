<?php
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/gala_events.obj.php';
    
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
        $gala = new GalaEvents();
        
        if ($gala->isInputValid($_POST["txtID"], $_POST["sltGala"], $_POST["sltStroke"], $_POST["sltLength"], $_POST["sltGender"], $_POST["txtAgeLower"], $_POST["txtAgeUpper"], $_POST['txtSubGroup'], $connection)) {
            $gala->setID($_POST['txtID']);
            $gala->setGalaID($_POST['sltGala']);
            $gala->setStrokeID($_POST['sltStroke']);
            $gala->setLengthID($_POST['sltLength']);
            $gala->setGender($_POST['sltGender']);
            if (!empty($_POST['txtAgeLower'])) {
                $gala->setAgeLower($_POST['txtAgeLower']);
            }
            if (!empty($_POST['txtAgeUpper'])) {
                $gala->setAgeUpper($_POST['txtAgeUpper']);
            }            
            $gala->setSubGroup($_POST['txtSubGroup']);
            
            if ($gala->create($connection)) {
                $_SESSION['create'] = true;

                header( 'Location:' . $domain . 'gala-events.php?id=' . $_GET["id"]);
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
    <title>Create | Gala Events | Bucksburn Amateur Swimming Club</title>
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
                <li><a href="../gala-events.php?id=<?php echo $_GET["id"]?>"role="link">Gala Events</a></li>
                <li class="current">Create a Gala Event</li>
            </ul>

            <h2>Create a Gala Event</h2>
            
            <?php
                require '../inc/forms.inc.php';
                require '../obj/galas.obj.php';
                require '../obj/venues.obj.php';
                require '../obj/strokes.obj.php';
                require '../obj/lengths.obj.php';
                require_once '../obj/members.obj.php';

                $conn = dbConnect();

                $galas = new Galas();
                $venues = new Venues();
                $strokes = new Strokes();
                $lengths = new Lengths();
                $event = new GalaEvents();
                $member = new Members();

                if (isset($_SESSION['create'])) {
                    echo '<p class="alert-box success radius centre">The Gala Event was added successfully!</p>';
                    unset($_SESSION['create']);
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

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if (empty($_POST["txtID"])) {
                        echo textInputEmptyError(true, "ID", "txtID", "errEmptyID", "Please enter an ID", 16,  "GalaEventID (E.g. IM100A1-100) (Stoke + length + ID)");
                    } else {
                        if ($event->isIDValid($_POST["txtID"],$_POST["sltGala"],$conn)) {
                            echo textInputPostback(true, "ID", "txtID", $_POST["txtID"], 16, false, "GalaEventID (E.g. IM100A1-100) (Stoke + length + ID)");
                        } else {
                            echo textInputPostbackError(true, "ID", "txtID", $_POST["txtID"], "errID","Please enter a unique ID",16);
                        }
                    }
                } else {
                    echo textInputBlank(true,"ID","txtID",16, false, "GalaEventID (E.g. IM100A1-100) (Stoke + length + ID)");
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if (empty($_POST["sltStroke"])) {
                        echo comboInputEmptyError(true, "Stroke", "sltStroke", "Please select a Stroke...", "errEmptyStroke","Please select a Stroke",$strokes->listAllStrokes($conn));
                    } else {
                        echo comboInputPostback(true, "Stroke", "sltStroke", $_POST["sltStroke"], $strokes->listAllStrokes($conn));
                    }                    
                } else {
                    echo comboInputBlank(true,"Stroke","sltStroke","Please select a Stroke...",$strokes->listAllStrokes($conn));
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if (empty($_POST["sltLength"])) {
                        echo comboInputEmptyError(true, "Length", "sltLength", "Please select a Length...", "Length","Please select a Length",$lengths->listAllLengths($conn));
                    } else {
                        echo comboInputPostback(true, "Length", "sltLength", $_POST["sltLength"], $lengths->listAllLengths($conn));
                    }                    
                } else {
                    echo comboInputBlank(true,"Length","sltLength","Please select a Length...",$lengths->listAllLengths($conn));
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if (empty($_POST["sltGender"])) {
                        echo comboInputEmptyError(true, "Gender", "sltGender", "Please select a Gender...", "Gender","Please select a Gender",$member->listGenders(true));
                    } else {
                        echo comboInputPostback(true,"Gender","sltGender",$_POST["sltGender"], $member->listGenders(true));
                    }                    
                } else {
                    echo comboInputBlank(true,"Gender","sltGender","Please select...", $member->listGenders(true));
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if ($event->isAgeLowerValid($_POST["txtAgeLower"])) {
                        echo numInputPostback(false, "Lower Age Limit</b> (leave blank if none exists)", "txtAgeLower", $_POST["txtAgeLower"], 8,15);
                    } elseif (!empty($_POST["txtAgeUpper"])) {
                        echo numInputPostbackError(false, "Lower Age Limit</b> (leave blank if none exists)", "txtAgeLower", $_POST["txtAgeLower"], 8,15,"errAgeLower","Please enter a valid number between 8 and 15");
                    } else {
                        echo numInputBlank(false,"Lower Age Limit</b> (leave blank if none exists)","txtAgeLower",8,15);
                    }
                } else {
                    echo numInputBlank(false,"Lower Age Limit</b> (leave blank if none exists)","txtAgeLower",8,15);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    if ($event->isAgeUpperValid($_POST["txtAgeUpper"])) {
                        echo numInputPostback(false, "Upper Age Limit</b> (leave blank if none exists)", "txtAgeUpper", $_POST["txtAgeUpper"], 8,15);
                    } elseif (!empty($_POST["txtAgeUpper"])) {
                        echo numInputPostbackError(false, "Upper Age Limit</b> (leave blank if none exists)", "txtAgeUpper", $_POST["txtAgeUpper"], 8,15,"errAgeLower","Please enter a valid number between 8 and 15");
                    } else {
                        echo numInputBlank(false,"Upper Age Limit</b> (leave blank if none exists)","txtAgeUpper",8,15);
                    }
                } else {
                    echo numInputBlank(false,"Upper Age Limit</b> (leave blank if none exists)","txtAgeUpper",8,15);
                }         

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltGala"])) {
                    echo textInputPostback(false, "Sub Group</b> (leave blank if none exists)", "txtSubGroup", $_POST["txtSubGroup"], 1);
                } else {
                    echo textInputBlank(false,"Sub Group</b> (leave blank if none exists)","txtSubGroup", 1);
                }              

                echo '</fieldset></div><div class="large-6 medium-6 small-12 right"><fieldset><legend>Gala Details</legend>';

                if (isset($_POST["sltGala"]) || isset($_GET["id"])) {
                    if (isset($_POST["sltGala"])) {
                        $galas->setID($_POST['sltGala']);
                    } else {
                        $galas->setID($_GET['id']);    
                    }
                    
                    $galas->getAllDetails($conn);
                    $venues->setID($galas->getVenueID());
                    $venues->getAllDetails($conn);
                        
                    echo linkButton("Add a new Gala", '../galas/create.php',true);
                    
                    echo comboInputPostback(true,"Gala","sltGala",$galas->getID(),$galas->listAllgalas($conn));
                    echo textInputSetup(false,"ID","txtGalaID",$galas->getID(),8,true);
                    echo textInputSetup(false,"Title","txtTitle",$galas->getTitle(),250,true);
                    echo textInputSetup(false,"Date","txtDate",date("d/m/Y", strtotime($galas->getDate())),50,true);
                    echo textInputSetup(false,"Venue","txtVenue",$venues->getVenue(),250,true);

                    echo '<div class="large-12 medium-12 small-12 columns"><a href="../galas/edit.php?id=' . $galas->getID() . '" class="medium radius button middle centre h6 capitalise">Edit this Gala</a></div>';
                } else {                    
                    echo linkButton("Add a new Gala", '../galas/create.php',true);
                    
                    if (isset($_POST["btnSubmit"])) {
                        echo comboInputEmptyError(true,"Gala","sltGala","Please select...","errEmptyGalaID","Please select a Gala",$galas->listAllgalas($conn));
                    } else {
                        echo comboInputBlank(true,"Gala","sltGala","Please select...",$galas->listAllgalas($conn)); 
                    }
                    echo textInputBlank(false,"ID","txtGalaID",8,true);
                    echo textInputBlank(false,"Title","txtTitle",250,true);
                    echo textInputBlank(false,"Date","txtDate",50,true);
                    echo textInputBlank(false,"Venue","txtVenue",250,true);
                }
                
                echo '</fieldset></div>';

                echo formEndWithButton("Add Gala Event");

            ?>
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
    
    <script>
        var attribute = document.createAttribute("onchange");
        attribute.value = "this.form.submit();";
        document.getElementById("sltGala").setAttributeNode(attribute);
    </script>
</body>

</html>
