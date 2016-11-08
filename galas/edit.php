<?php
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/galas.obj.php';
    
    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!galaFullAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }


    if (is_null($_GET["id"])) {        
        header( 'Location:' . $domain . '404.php' );
        exit;
    } else {
        $galas = new Galas();
        if (!$galas->doesExist($connection,$_GET["id"])) {
            header( 'Location:' . $domain . '404.php' );
            exit;
        }
    }

    if (isset($_POST['btnSubmit'])) {
        

        $gala = new Galas($_GET['id']);
        
        if ($gala->isInputValid($connection,$_POST['txtID'], $_POST['txtTitle'], $_POST['txtDescription'], $_POST['txtDate'],$_POST['sltVenue'],$_POST['txtWarmUp'],$_POST['txtOrganiser'],$_POST['txtConfirm'],$_POST['txtCutOff'],$_POST['txtFees'],$_POST['txtNotes'])) {
            $gala->setID($_POST['txtID']);
            $gala->setTitle($_POST['txtTitle']);
            $gala->setDescription($_POST['txtDescription']);
            $gala->setDate($_POST['txtDate']);
            if (isset($_POST["rdoAccredited"])) {
                foreach ($_POST["rdoAccredited"] as $key => $value) {
                    $gala->setIsAccredited($value);
                }
            } else {
                $gala->setIsAccredited(null);
            }
            if (isset($_POST["rdoCourseType"])) {
                foreach ($_POST["rdoCourseType"] as $key => $value) {
                    $gala->setIsLongCourse($value);
                }
            } else {
                $gala->setIsLongCourse(null);
            }
            $gala->setVenueID($_POST['sltVenue']);
            $gala->setWarmUpTime($_POST['txtWarmUp']);
            $gala->setOrganiser($_POST['txtOrganiser']);            
            $gala->setConfirmationDate($_POST['txtConfirm']);
            $gala->setCutOffDate($_POST['txtCutOff']);
            $gala->setFees($_POST['txtFees']);
            $gala->setNotes($_POST['txtNotes']); 
                        
            if ($gala->update($connection)) {
                $_SESSION['update'] = true;

                header('Location:' .$domain . 'galas/view.php?id=' . $gala->getID());
                die();
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
    <title>Edit | Gala | Bucksburn Amateur Swimming Club</title>
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
                <li><a href="../galas.php" role="link">Galas</a></li>
                <li class="current">Edit a Gala</li>
            </ul>
    
            <h2>Edit a Gala</h2>
            
            <?php
                require '../inc/forms.inc.php';
                require '../obj/venues.obj.php';

                $conn = dbConnect();

                $galaItem = new Galas($_GET["id"]);
                $galaItem->getAllDetails($conn);
                $venues = new Venues();

                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error editing this gala. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                echo formStart();
                echo '<div class="large-6 medium-6 small-12 left"><fieldset><legend>Gala Details</legend>';

                echo textInputSetup(true, "Gala ID", "txtID", $galaItem->getID(), 8, true);
                
                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (empty($_POST["txtTitle"])) {
                        echo textInputEmptyError(true, "Title", "txtTitle", "errEmptyTitle", "Please enter a Title", 250);
                    } else {
                        echo textInputPostback(true, "Title", "txtTitle", $_POST["txtTitle"], 250);
                    }
                } else {
                    echo textInputSetup(true,"Title","txtTitle",$galaItem->getTitle(),250);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    echo textareaInputPostback(false, "Description", "txtDescription", $_POST["txtDescription"], 250,3);
                } else {
                    echo textareaInputSetup(false,"Description","txtDescription",$galaItem->getDescription(),250,3);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (empty($_POST["txtDate"])) {
                        echo dateInputEmptyError(true, "Date", "txtDate", "errEmptyDate", "Please enter a Date",null,null);
                    } else {
                        echo dateInputPostback(true, "Date", "txtDate", $_POST["txtDate"],null,null);
                    }
                } else {
                    echo dateInputSetup(true,"Date","txtDate",$galaItem->getDate(),null,null);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (isset($_POST["rdoAccredited"])) {
                        echo radioInputPostback(false, "Accreditation", "rdoAccredited", $_POST["rdoAccredited"],array(true=>"Accredited",false=>"Non-Accredited"));
                    } else {
                        echo radioInputBlank(false,"Accreditation","rdoAccredited",array(true=>"Accredited",false=>"Non-Accredited"));
                    }
                } else {
                    if (!is_null($galaItem->getIsAccredited())) {
                        echo radioInputSetup(false,"Accreditation","rdoAccredited",$galaItem->getIsAccredited(),array(true=>"Accredited",false=>"Non-Accredited"));
                    } else {
                        echo radioInputBlank(false,"Accreditation","rdoAccredited",array(true=>"Accredited",false=>"Non-Accredited"));
                    }
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (isset($_POST["rdoCourseType"])) {
                        echo radioInputPostback(false,"Course Type","rdoCourseType",$_POST["rdoCourseType"],array(true=>"Long Course",false=>"Short Course"));
                    } else {
                        echo radioInputBlank(false,"Course Type","rdoCourseType",array(true=>"Long Course",false=>"Short Course"));
                    }                 
                } else {
                    if (!is_null($galaItem->getIsLongCourse())) {
                        echo radioInputSetup(false,"Course Type","rdoCourseType",$galaItem->getIsLongCourse(),array(true=>"Long Course",false=>"Short Course"));
                    } else {
                        echo radioInputBlank(false,"Course Type","rdoCourseType",array(true=>"Long Course",false=>"Short Course"));
                    }                    
                }
                
                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (empty($_POST["txtWarmUp"])) {
                        echo textInputEmptyError(true, "WarmUp Time", "txtWarmUp", "errEmptyWarmUp", "Please enter a WarmUp Time", 11);
                    } else {
                        echo textInputPostback(true, "WarmUp Time", "txtWarmUp", $_POST["txtWarmUp"], 11);
                    }
                } else {
                    echo textInputSetup(true,"WarmUp Time","txtWarmUp",$galaItem->getWarmUpTime(),11);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (empty($_POST["txtOrganiser"])) {
                        echo textInputEmptyError(true, "Organiser", "txtOrganiser", "errEmptyOrganiser", "Please enter an Organiser", 50);
                    } else {
                        echo textInputPostback(true, "Organiser", "txtOrganiser", $_POST["txtOrganiser"], 50);
                    }
                } else {
                    echo textInputSetup(true,"Organiser","txtOrganiser",$galaItem->getOrganiser(),50);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (empty($_POST["txtConfirm"])) {
                        echo dateInputEmptyError(true, "Confirmation Date", "txtConfirm", "errEmptyConfirm", "Please enter a Confirmation Date",null,null);
                    } else {
                        echo dateInputPostback(true, "Confirmation Date", "txtConfirm", $_POST["txtConfirm"],null,null);
                    }
                } else {
                    echo dateInputSetup(true,"Confirmation Date","txtConfirm",$galaItem->getConfirmationDate(),null,null);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    if (empty($_POST["txtCutOff"])) {
                        echo dateInputEmptyError(true, "Cut Off Date", "txtCutOff", "errEmptyCutOff", "Please enter a Cut Off Date",null,null);
                    } else {
                        echo dateInputPostback(true, "Cut Off Date", "txtCutOff", $_POST["txtCutOff"],null,null);
                    }
                } else {
                    echo dateInputSetup(true,"Cut Off Date","txtCutOff",$galaItem->getCutOffDate(),null,null);
                }                
                
                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    echo moneyInputPostback(false, "Fees", "txtFees", $_POST["txtFees"], 6);
                } else {
                    echo moneyInputSetup(false,"Fees","txtFees",$galaItem->getFees(),6);
                }

                if (isset($_POST["btnSubmit"]) || isset($_POST["sltVenue"])) {
                    echo textareaInputPostback(false, "Notes", "txtNotes", $_POST["txtNotes"], 5000,8);
                } else {
                    echo textareaInputSetup(false,"Notes","txtNotes",$galaItem->getNotes(),5000,8);
                }

                echo '</fieldset></div><div class="large-6 medium-6 small-12 right"><fieldset><legend>Venue Details</legend>';

                if (isset($_POST["sltVenue"])) {
                    $venues->setID($_POST['sltVenue']);
                    $venues->getAllDetails($conn);
                        
                    echo linkButton("Add a new Venue", '../venues/create.php',true);
                    
                    echo comboInputPostback(true,"Venue","sltVenue",$_POST["sltVenue"],$venues->listAllVenues($conn)); 
                } else {                    
                    $venues->setID($galaItem->getVenueID());
                    $venues->getAllDetails($conn);

                    echo linkButton("Add a new Venue", '../venues/create.php',true);
                    
                    echo comboInputPostback(true,"Venue","sltVenue",$galaItem->getVenueID(),$venues->listAllVenues($conn)); 
                }

                echo textInputSetup(true,"Address Line 1","txtAddress1",$venues->getAddress1(),50,true);
                echo textInputSetup(false,"Address Line 2","txtAddress2",$venues->getAddress2(),50,true);
                echo textInputSetup(true,"City","txtCity",$venues->getCity(),50,true);
                echo textInputSetup(false,"County","txtCounty",$venues->getCounty(),50,true);
                echo textInputSetup(true,"Postcode","txtPostcode",$venues->getPostcode(),8,true);
                echo telInputSetup(false, "Telephone", "txtTelephone", $venues->getTelephone(), 12, true);
                echo emailInputSetup(false, "Email", "txtEmail", $venues->getEmail(), 250, true);
                echo textInputSetup(false, "Website", "txtWebsite", $venues->getWebsite(), 250, true); 

                echo '</fieldset></div>';

                echo formEndWithButton("Save changes","delete.php?id=" . $galaItem->getID());

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
