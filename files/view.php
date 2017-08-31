<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 05/08/2017
 * Time: 21:11
 * File view/download script
 */


    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/files.obj.php';

    // Check for a parameter before we send the header
    if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {
        header('Location:' . $domain . '404.php');
        exit;
    } else {
        $connection = dbConnect();
        $files= new files($_GET["id"]);
        $files->getAllDetails($connection);
        if (!$files->doesExist($connection)) {
            header('Location:' . $domain . '404.php');
            exit;
        }

        if (!isset($_SESSION['username']) && $files->getVisibility()  == 0) {
            header('Location:' . $domain . 'login.php');
            exit;
        }
    }

?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php';?>
    <title>Download | Files | Bucksburn Amateur Swimming Club</title>
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
            <li>Files</li>
            <li class="current">Download File</li>
        </ul>



        <?php

        $conn = dbConnect();

        $files = new files($_GET["id"]);
        $files->getAllDetails($conn);
        $fileLink = $domain .$files->getFilePath();
        $downloadLink =$domain. 'files/download.php?id='.$files->getFileID();

        echo'<h2>Downloading File: '.$files->getTitle().'...</h2>';

        echo '<p>'.$files->getDescription().'</p>';

        echo'<p>If file fails to download after 5 seconds then click <a href="'.$fileLink.'">Here</a></p>';


        echo '<META HTTP-EQUIV="Refresh" CONTENT="5; URL='.$downloadLink.'">';

        echo '<a href="javascript: history.go(-1)" class="button">Go Back</a>';
        dbClose($conn);
        ?>

    </div>
</div>
<?php include '../inc/footer.inc.php';?>
</body>

</html>
