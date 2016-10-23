<?php 
    session_start(); 

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/news.obj.php';

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
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>View | News | Bucksburn Amatuer Swimming Club</title> 
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
                <li class="current">
                    
                    <?php  
                        require_once '../obj/members.obj.php';
                        require '../inc/forms.inc.php';

                        $conn = dbConnect();

                        $newsItem = new News($_GET["id"]);
                        $newsItem->getAllDetails($conn);
                        $memberItem = new Members($newsItem->getAuthor());

                echo $newsItem->getTitle();
                echo '</li></ul>';

                if (isset($_SESSION['create'])) {
                    echo '<p class="alert-box success radius centre">Item added successfully!</p>';
                    unset($_SESSION['create']);
                }
                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">Changes saved successfully!</p>';
                    unset($_SESSION['update']);
                }

                echo ' <div>
                <article class="newsView">

                <h2>' . $newsItem->getTitle() . '</h2>
                <h3 class="h4 capitalise">' . $newsItem->getSubtitle() . '</h3>
                <h4 class="h5 italic">By ' . $memberItem->getFullNameByUsername($conn) . ' on ' . date("l jS F Y", strtotime($newsItem->getDate())) . '</h4>';

                //<p>' . nl2br($newsItem->getMainBody()) . '</p>
                echo '<p>' . $newsItem->getMainBody() . '</p>

                </article>
                </div>';
                if (isset($_SESSION["username"])) {
                    echo linkButton("Edit this News Article", 'edit.php?id=' . $newsItem->getID(),true);
                }

                    ?>
            </div>
        </div>
        <?php include '../inc/footer.inc.php';?>
</body>

</html>
