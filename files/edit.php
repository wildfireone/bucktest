<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 05/08/2017
 * Time: 21:11
 */


session_start();
require '../inc/connection.inc.php';
require '../inc/security.inc.php';


if (isset($_SESSION['username'])) {

    require '../obj/files.obj.php';

    if (!filesFullAccess($connection, $currentUser, $memberValidation)) {
        header('Location:' . $domain . 'message.php?id=badaccess');
    }

    $conn = dbConnect();

    $files = new files($_GET['id']);
    $files->getAllDetails($conn);



    if (!$files->doesExist($conn)) {
        header('Location:' . $domain . '404.php');
    }

    $members = new members($_SESSION['username']);
    $members->getAllDetails($conn);


    if (isset($_POST['btnSubmit'])) {
        $files = new files(htmlentities($_GET['id']));
        $files->getAllDetails($conn);
        if (isset($_POST['txtTitle']) && (isset($_POST['txtDescription']))) {

            $files->setTitle(htmlentities($_POST['txtTitle']));
            $files->setDescription(htmlentities($_POST['txtDescription']));
            $files->setVisibility(htmlentities($_POST['chkVisibility']));

            if ($files->update($conn)) {
                $_SESSION['update'] = true;
                header('Location:' . $domain . 'files');
            }
        } else {
            $_SESSION['error'] = true;
        }
    }

    if (isset($_POST['btnDelete'])) {

        $files = new files(htmlentities($_GET['id']));
        $files->getAllDetails($conn);


        //Finally delete album
        if ($files->delete($conn)) {
            $_SESSION['delete'] = true;
            header('Location:' . $domain . 'files');
        } else {
            $_SESSION['errorDelete'] = true;
        }
    }

} else {
    header('Location:' . $domain . 'login.php');
}


?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Edit File| Bucksburn Amateur Swimming Club</title>
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
            <li><a href="../files/" role="link">Files</a></li>
            <li><a href="../files/view.php?id=<?= $files->getFileID() ?>" role="link">View File</a></li>
            <li class="current">Edit File</li>
        </ul>

        <?php

        if (isset($_SESSION['error'])) {
            echo '<br/>';
            echo '<div class="callout warning">
          <h5>File Update Failed</h5>
          <p>Form incomplete, errors are highlighted below.</p>
          </div>';
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['errorDelete'])) {
            echo '<br/>';
            echo '<div class="callout warning">
          <h5>File deletion failed</h5>
          <p>Form incomplete, errors are highlighted below.</p>
          </div>';
            unset($_SESSION['errorDelete']);
        }

        if (isset($_SESSION['upload'])) {
            echo '<p class="alert-box success radius centre">File uploaded successfully!</p>';
            unset($_SESSION['upload']);
        }

        ?>
        <h2 class="text-center">Files | Edit file: <?= $files->getTitle() ?></h2>

        <div class="small-6 small-centered large-10 large-centered columns">
            <form action="" method="post">
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>File Title</b></span>
                            <input type="text" id="txtName" name="txtTitle" maxlength="20"
                                   value="<?= $files->getTitle() ?>"/>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Album Description</b></span>
                            <textarea id="txtDescription" name="txtDescription" rows="4"
                                      maxlength="250"><?= $files->getDescription() ?></textarea>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label><b>
                                <span class="required">* </span>Visibility (Publicly accessible?)</b>
                            <input type='hidden' value='0' name='chkVisibility'/>
                            <input id="chkVisibility" type="checkbox" name="chkVisibility"
                                   value="1" <?php echo($files->getVisibility() == 1 ? 'checked' : ''); ?>/>

                        </label>
                    </div>
                </div>

                <div class="large-12 medium-12 small-12 columns">
                    <div class="row">
                        <input class="success button" type="submit" name="btnSubmit" value="Update File">
                        <input type="submit" name="btnDelete" class="alert button" value="Delete File"
                               onclick="return confirm('Are you sure? This WILL delete this file from database and web server')">
                    </div>
                    <div class="row">
                        <input class="button" type="reset" value="Reset">
                    </div>
                </div>
        </div>
        </form>
    </div>

</div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>