<?php
    session_start();

    require '../inc/connection.inc.php';
    require '../obj/venues.obj.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $venues = new Venues();

        if ($venues->isInputValid($_POST['txtVenue'],$_POST['txtAddress1'],$_POST['txtAddress2'],$_POST['txtCity'],$_POST['txtCounty'],$_POST['txtPostcode'],$_POST['txtTelephone'],$_POST['txtEmail'],$_POST['txtWebsite'])) {
            $venues->setVenue($_POST['txtVenue']);
            $venues->setAddress1($_POST['txtAddress1']);
            $venues->setAddress2($_POST['txtAddress2']);
            $venues->setCity($_POST['txtCity']);
            $venues->setCounty($_POST['txtCounty']);
            $venues->setPostcode($_POST['txtPostcode']);
            $venues->setTelephone($_POST['txtTelephone']);
            $venues->setEmail($_POST['txtEmail']);
            $venues->setWebsite($_POST['txtWebsite']);
            
            if ($venues->create($connection)) {
                $_SESSION['create'] = true;

                header('Location:' .$domain . '/venues/view.php?id=' . $venues->getID());
                die();
            } else {
                $_SESSION['error'] = true;
            }
        } else {
            $_SESSION['invalid'] = true;
        }
        dbClose($connection);
    }    
?>

<!DOCTYPE html>
<html lang="en-GB">

<head>    
    <?php include '../inc/meta.inc.php'; ?>
    <title>View | Venues | Bucksburn Amateur Swimming Club</title>   
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>    
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
        
            <h2>Create a Venue</h2>    
            
             <?php
                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error adding the Venue. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                require '../inc/forms.inc.php';

                $conn = dbConnect();
                $venue = new Venues();

                echo '<div class="large-9 large-centered medium-12 small-12 columns"><fieldset><legend>Venue Details</legend>';
                echo formStart();

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtVenue"])) {
                        echo textInputEmptyError(true, "Venue Name", "txtVenue", "errEmptyVenue", "Please enter a Venue Name", 250);
                    } else {
                        echo textInputPostback(true, "Venue Name", "txtVenue", $_POST['txtVenue'], 250);
                    }
                } else {
                    echo textInputBlank(true, "Venue Name", "txtVenue", 250);
                }


                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtAddress1"])) {
                        echo textInputEmptyError(true, "Address Line 1", "txtAddress1", "errEmptyAddress1", "Please enter an Address Line 1", 50);
                    } else {
                        echo textInputPostback(true,"Address Line 1","txtAddress1",$_POST["txtAddress1"],50);
                    }
                } else {
                    echo textInputBlank(true,"Address Line 1","txtAddress1",50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"Address Line 2","txtAddress2",$_POST["txtAddress2"],50);
                } else {
                    echo textInputBlank(false,"Address Line 2","txtAddress2",50);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtCity"])) {
                        echo textInputEmptyError(true, "City", "txtCity", "errEmptyCity", "Please enter a City", 50);
                    } else {
                        echo textInputPostback(true,"City","txtCity",$_POST["txtCity"],50);
                    }
                } else {
                    echo textInputBlank(true,"City","txtCity",50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false,"County","txtCounty",$_POST["txtCounty"],50);
                } else {
                    echo textInputBlank(false,"County","txtCounty",50);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtPostcode"])) {
                        echo textInputEmptyError(true, "Postcode", "txtPostcode", "errEmptyPostcode", "Please enter a Postcode", 8);
                    } else {
                        if ($venue->isPostcodeValid($_POST["txtPostcode"])) {
                            echo textInputPostback(true,"Postcode","txtPostcode",$_POST["txtPostcode"],8);
                        } else {
                            echo textInputPostbackError(true,"Postcode","txtPostcode",$_POST["txtPostcode"],"errPostcode", "Please enter a valid Postcode",8);
                        }
                    }
                } else {
                    echo textInputBlank(true,"Postcode","txtPostcode",8);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if ($venue->isTelephoneValid($_POST["txtTelephone"])) {
                        echo telInputPostback(false,"Telephone","txtTelephone",$_POST["txtTelephone"],12);
                    } else {
                        echo telInputPostbackError(false,"Telephone","txtTelephone",$_POST["txtTelephone"],"errTelephone","Please enter a valid Telephone number",12);
                    }                    
                } else {
                    echo telInputBlank(false,"Telephone","txtTelephone",12);
                }
                
                if (isset($_POST["btnSubmit"])) {
                    if ($venue->isEmailValid($_POST["txtEmail"])) {
                        echo emailInputPostback(false,"Email","txtEmail",$_POST["txtEmail"],250);
                    } else {
                        echo emailInputPostbackError(false,"Email","txtEmail",$_POST["txtEmail"],"errEmail","Please enter a valid Email Address",250);
                    }                    
                } else {
                    echo emailInputBlank(false,"Email","txtEmail",250);
                }

                if (isset($_POST["btnSubmit"])) {
                    if ($venue->isWebsiteValid($_POST["txtWebsite"])) {
                        echo textInputPostback(false,"Website","txtWebsite",$_POST["txtWebsite"],250);
                    } else {
                        echo textInputPostbackError(false,"Website","txtWebsite",$_POST["txtWebsite"],"errWebsite","Please enter a valid Website URL",250);
                    }
                } else {
                    echo textInputBlank(false,"Website","txtWebsite",250);
                }                  

                echo formEndWithButton("Add Venue");      
                echo '</fieldset></div>';

                dbClose($conn);
            ?> 
                    
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
