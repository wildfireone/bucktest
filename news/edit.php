<?php
    session_start();
        // Check for a parameter before we send the header
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


    // Check for a parameter before we send the header
    if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {        
            header( 'Location:' . $domain . '404.php' );
            exit;
    } else {
        $connection = dbConnect();
        $news = new News($_GET["id"]);
        if (!$news->doesExist($connection)) {
            header( 'Location:' . $domain . '404.php' );
            exit;
        }

    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $newsItem = new News($_GET["id"]);
        
        if ($newsItem->isInputValid($_POST['txtTitle'], $_POST['txtSubTitle'], $_POST['txtMainBody'])) {
            $newsItem->setTitle($_POST['txtTitle']);
            $newsItem->setSubTitle($_POST['txtSubTitle']);
            $newsItem->setAuthor($_SESSION['username']);
            $newsItem->setMainBody($_POST['txtMainBody']);
            
            if ($newsItem->update($connection)) {
                $_SESSION['update'] = true;

                header('Location:' .$domain . 'news/view.php?id=' . $newsItem->getID());
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
    <title>Edit | News | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>   
    <link href="../css/site.css" rel="stylesheet"/>
    <script src='../tinymce/tinymce.min.js'></script>
    <script>
        tinymce.init({
            selector: '#txtMainBody',
            plugins: 'advlist, table, autolink, code, contextmenu, imagetools, fullscreen, hr,  colorpicker, preview, spellchecker, link, autosave, lists, visualblocks'

        });
    </script>
</head>

<body>   
    <?php include '../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="../index.php" role="link">Home</a></li>
                <li><a href="../news.php" role="link">News</a></li>
                <li class="current">Edit a News Item</li>
            </ul>

            <h2>Edit a News Item</h2>
            
            <?php
                require '../inc/forms.inc.php';

                $conn = dbConnect();

                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error creating the news item. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                echo formStart();

                $news = new News($_GET["id"]);
                $news->getAllDetails($conn);

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtTitle"])) {
                        echo textInputEmptyError(true, "News Title", "txtTitle", "errEmptyTitle", "Please enter a News Title", 100);
                    } else {
                        echo textInputPostback(true, "News Title", "txtTitle", $_POST["txtTitle"], 100);
                    }
                } else {
                    echo textInputSetup(true, "News Title", "txtTitle", $news->getTitle(), 100);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textInputPostback(false, "News Subtitle", "txtSubTitle", $_POST["txtSubTitle"], 100);
                } else {
                    echo textInputSetup(false, "News Subtitle", "txtSubTitle", $news->getSubTitle(),100);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtMainBody"])) {
                        echo textareaInputEmptyError(true, "Main Body", "txtMainBody", "errEmptyBody", "Please enter a Main Body", 5000, 15);
                    } else {
                        echo textareaInputPostback(true, "Main Body", "txtMainBody", $_POST["txtMainBody"], 5000, 15);
                    }
                } else {
                    echo textareaInputSetup(true, "Main Body", "txtMainBody", $news->getMainBody(), 5000, 15);
                }
            
                echo formEndWithButton("Save changes","delete.php?id=" . $news->getID());

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
