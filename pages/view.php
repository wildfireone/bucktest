<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 03/08/2017
 * Time: 21:16
 * View page template
 */


session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/pages.obj.php';


// Check for a parameter before we send the header
if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {
    header('Location:' . $domain . '404.php');
    exit;
} else {
    $connection = dbConnect();
    $pages = new pages($_GET["id"]);
    $pages->getAllDetails($connection);

    if (!$pages->doesExist($connection)) {
        header('Location:' . $domain . '404.php');
        exit;
    }

    if (!isset($_SESSION['username']) && $pages->getVisibility() == 0) {
        header('Location:' . $domain . 'login.php');
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title><?php echo $pages->getPageTitle() ?> | Bucksburn Amateur Swimming Club</title>
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet"/>
</head>

<body>
<?php include '../inc/header.inc.php'; ?>
<br>

<div class="row" id="content">
    <div class="large-12 medium-12 small-12 columns">

        <ul class="breadcrumbs">
            <li><a href="../index.php" role="link">Home</a></li>
            <?php
            if (isset($_SESSION["username"]) && (pagesFullAccess($connection, $currentUser, $memberValidation))) {
                echo '<li><a href="../pages/" role="link">Pages</a></li>';
            } else {
                echo ' <li>Pages</li>';
            }
            ?>

            <li class="current">

                <?php
                require_once '../obj/members.obj.php';
                require '../inc/forms.inc.php';

                $conn = dbConnect();


                $pages = new pages();
                $pages->setPageID(($_GET["id"]));
                $pages->getAllDetails($conn);

                $memberItem = new Members($pages->getUserID());
                $memberItem->getAllDetails($conn);


                echo $pages->getPageTitle();
                echo '</li></ul>';

                if (isset($_SESSION['create'])) {
                    echo '<p class="alert-box success radius centre">Pages added successfully!</p>';
                    unset($_SESSION['create']);
                }
                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">Changes saved successfully!</p>';
                    unset($_SESSION['update']);
                }

                echo ' <div>
                <article>

                <h2>' . $pages->getPageTitle() . '</h2>';

                echo '<p>' . $pages->getPageContent() . '</p>
                <h4 class="h5 italic">By ' . $memberItem->getFullNameByUsername($conn) . ' on ' . $pages->getCreatedDate() . '</h4>
                <h4 class="h5 italic">Last Updated: ' . $pages->getModifiedDate() . '</h4>
                </article>
                
                ';
                if (isset($_SESSION["username"]) && (pagesFullAccess($connection, $currentUser, $memberValidation))) {
                    echo linkButton("Edit this Page", 'edit.php?id=' . $pages->getPageID(), false);
                }
                ?>
    </div>
</div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>
