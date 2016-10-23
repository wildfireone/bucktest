<?php
    session_start();

    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';
    require 'inc/functions.inc.php';
    
    if (isset($_POST["btnSubmit"])) {
        if (!empty($_POST['txtName']) && isEmailValid($_POST['txtEmail']) && !empty($_POST['txtSubject']) && !empty($_POST['txtMessage'])) {
            $to = $admin;            
            $subject = htmlspecialchars($_POST['txtSubject']);
            $headers = "From: " . htmlspecialchars($_POST['txtEmail']);
            $message = htmlspecialchars($_POST['txtMessage']) . PHP_EOL . PHP_EOL . htmlspecialchars($_POST['txtName']);
            
            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['sent'] = true;
            } else {
                $_SESSION['error'] = true;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Contact | Bucksburn Amatuer Swimming Club</title>  
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>     
    <link href="css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include 'inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="index.php" role="link">Home</a></li>
                <li class="current">Contact</li>
            </ul>
        
            <h2>Contact</h2>
            
            <p>If you'd like to contact us, please do so using the form below and we will get back to you as quickly as possible.</p>
            
            <div class="large-9 large-centered medium-12 small-12 columns">
                        
            <?php
                require 'inc/forms.inc.php';

                if (isset($_SESSION['sent'])) {
                    echo '<p class="alert-box success radius centre">Your message was sent successfully!</p>';
                    if (isset($_POST)) { unset($_POST); }
                    unset($_SESSION['sent']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">Your message could not be sent. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                echo formStart();

                if (isset($_POST["btnSubmit"])) {
                        if (empty($_POST["txtName"])) {
                            echo textInputEmptyError(true, "Your Name", "txtName", "errEmptyName", "Please enter your name", 50);
                        } else {
                            echo textInputPostback(true, "Your Name", "txtName", $_POST["txtName"], 50);
                        }
                } else {
                    echo textInputBlank(true, "Your Name", "txtName", 50);
                }

                if (isset($_POST["btnSubmit"])) {
                        if (empty($_POST["txtEmail"])) {
                            echo emailInputEmptyError(true, "Your Email", "txtEmail", "errEmptyEmail", "Please enter your email address", 250);
                        } else {
                            if (isEmailValid($_POST['txtEmail'])) {
                                echo emailInputPostback(true, "Your Email", "txtEmail", $_POST["txtEmail"], 250);
                            } else {
                                echo emailInputPostbackError(true, "Your Email", "txtEmail", $_POST["txtEmail"], "errEmailInvalid", "Please enter a valid Email Address", 250);
                            }
                        }
                } else {
                    echo emailInputBlank(true, "Your Email", "txtEmail", 250);
                }

                if (isset($_POST["btnSubmit"])) {
                        if (empty($_POST["txtSubject"])) {
                            echo textInputEmptyError(true, "Message Subject", "txtSubject", "errEmptySubject", "Please enter the message subject", 250);
                        } else {
                            echo textInputPostback(true, "Message Subject", "txtSubject", $_POST["txtSubject"], 250);
                        }
                } else {
                    echo textInputBlank(true, "Message Subject", "txtSubject", 250);
                }

                if (isset($_POST["btnSubmit"])) {
                        if (empty($_POST["txtMessage"])) {
                            echo textareaInputEmptyError(true, "Your Message", "txtMessage", "errEmptyMessage", "Please enter your message", 2500, 10);
                        } else {
                            echo textareaInputPostback(true, "Your Message", "txtMessage", $_POST["txtMessage"], 2500, 10);
                        }
                } else {
                    echo textareaInputBlank(true, "Your Message", "txtMessage", 2500, 10);
                }

                echo formEndWithButton("Send message");
            ?>
            
            </div>
             
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
