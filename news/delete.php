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
        
        if ($newsItem->delete($connection)) {
            $_SESSION['delete'] = true;

            header('Location:' .$domain . 'news.php');
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
    <title>Delete | News | Bucksburn Amatuer Swimming Club</title> 
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
                <li class="current">Delete a News Item</li>
            </ul>

            <h2>Delete a News Item</h2>
            
            <?php
                require '../inc/forms.inc.php';

                $conn = dbConnect();

                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the news item. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                $news = new News($_GET["id"]);
                $news->getAllDetails($conn);

                echo formStart(false);

                echo '<div class="panel"><p class="centre middle">Are you sure you want to delete the news item <b>' . $news->getTitle() . '</b>?</p></div>';

                echo formEndWithDeleteButton("Delete");

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
