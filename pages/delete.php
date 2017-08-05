<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 03/08/2017
 * Time: 21:16
 */

    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/pages.obj.php';

    if (!isset($_SESSION['username'])) {
        header('Location:' . $domain . 'message.php?id=badaccess');
        exit;
    }

    if (!pagesFullAccess($connection, $currentUser, $memberValidation)) {
        header('Location:' . $domain . 'message.php?id=badaccess');
        exit;
    }


    // Check for a parameter before we send the header
    if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {
        header('Location:' . $domain . '404.php');
        exit;
    } else {
        $connection = dbConnect();
        $pages = new pages($_GET["id"]);
        if (!$pages->doesExist($connection)) {
            header('Location:' . $domain . '404.php');
            exit;
        }
    }

    if (isset($_POST['btnSubmit'])) {

        $connection = dbConnect();
        $pages = new pages($_GET["id"]);

        if ($pages->delete($connection)) {
            $_SESSION['delete'] = true;

            header('Location:' .$domain . 'pages/index.php');
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
    <title>Delete | Pages | Bucksburn Amateur Swimming Club</title>
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
            <li><a href="/pages/view.php?id=<?php echo $pages->getPageID();?>" role="link">Page</a></li>
            <li class="current">Delete a Page</li>
        </ul>

        <h2>Delete a Page Item</h2>

        <?php
        require '../inc/forms.inc.php';

        $conn = dbConnect();

        if (isset($_SESSION['error'])) {
            echo '<p class="alert-box error radius centre">There was an error deleting the pages item. Please try again.</p>';
            unset($_SESSION['error']);
        }

        $pages = new pages($_GET["id"]);
        $pages->getAllDetails($conn);

        echo formStart(false);

        echo '<div class="panel"><p class="centre middle">Are you sure you want to delete the page: <b>' . $pages->getPageTitle() . '</b>?</p></div>';

        echo formEndWithDeleteButton("Delete");

        dbClose($conn);
        ?>

    </div>
</div>
<?php include '../inc/footer.inc.php';?>
</body>

</html>
