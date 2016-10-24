<?php
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/news.obj.php';
    
    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!newsFullAccess($connection, $currentUser, $memberValidation)) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $news = new News();
        
        if ($news->isInputValid($_POST['txtTitle'], $_POST['txtSubTitle'], $_POST['txtMainBody'])) {
            $news->setTitle($_POST['txtTitle']);
            $news->setSubTitle($_POST['txtSubTitle']);
            $news->setAuthor($_SESSION['username']);
            $news->setMainBody($_POST['txtMainBody']);
            
            if ($news->create($connection)) {
                $_SESSION['create'] = true;

                header('Location:' .$domain . '/news/view.php?id=' . $news->getID());
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
    <?php include '../inc/meta.inc.php';?>
    <title>Create | News | Bucksburn Amatuer Swimming Club</title> 
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
                <li><a href="../news.php" role="link">News</a></li>
                <li class="current">Create a News Item</li>
            </ul>    
                
            <h2>Create a News Item</h2>
            
            <?php
                require '../inc/forms.inc.php';

                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error creating the news item. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                echo formStart();

                $newsValidation = new News();

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtTitle"])) {
                        echo textInputEmptyError(true, "News Title", "txtTitle", "errEmptyTitle", "Please enter a News Title", 100);
                    } else {
                        echo textInputPostback(true, "News Title", "txtTitle", $_POST["txtTitle"], 100);
                    }
                } else {
                    echo textInputBlank(true, "News Title", "txtTitle", 100);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false, "News Subtitle", "txtSubTitle", $_POST["txtSubTitle"], 100);
                } else {
                    echo textInputBlank(false, "News Subtitle", "txtSubTitle", 100);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtMainBody"])) {
                        echo textareaInputEmptyError(true, "Main Body", "txtMainBody", "errEmptyBody", "Please enter a Main Body", 5000, 15);
                    } else {
                        echo textareaInputPostback(true, "Main Body", "txtMainBody", $_POST["txtMainBody"], 5000, 15);
                    }
                } else {
                    echo textareaInputBlank(true, "Main Body", "txtMainBody", 5000, 15);
                }
            
                echo formEndWithButton("Add News Item");
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
