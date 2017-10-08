<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 05/08/2017
 * Time: 21:11
 * Files index
 */


session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/files.obj.php';

if (!isset($_SESSION['username'])) {
    header('location: ' . $domain . 'message.php?id=badaccess');
    die();
}

if (!pagesFullAccess($connection, $currentUser, $memberValidation)) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Files | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet"/>
</head>

<body>
<?php include '../inc/header.inc.php'; ?>
<br>
<div class="row" id="content">
    <div class="row-12 columns">

        <ul class="breadcrumbs">
            <li><a href="index.php" role="link">Home</a></li>
            <li class="current">Files</li>
        </ul>

        <h2>Files List</h2>

        <?php

        if (isset($_SESSION['delete'])) {
            echo '<p class="alert-box success radius centre">File deleted successfully!</p>';
            unset($_SESSION['delete']);
        }

        if (isset($_SESSION['update'])) {
            echo '<p class="alert-box success radius centre">Changes saved!</p>';
            unset($_SESSION['update']);
        }

        if (isset($_SESSION['upload'])) {
            echo '<p class="alert-box success radius centre">File uploaded successfully!</p>';
            unset($_SESSION['upload']);
        }

        ?>

        <table class="large-12 medium-12 small-12 columns">
            <tr>
                <th>File Title</th>
                <th>File Description</th>
                <th>Uploader</th>
                <th>Type</th>
                <th>Visibility</th>
                <th>View</th>
                <th>Edit</th>
            </tr>
            <?php
            require_once '../obj/members.obj.php';

            $conn = dbConnect();

            $files = new files();
            $member = new Members();
            $fileList = $files->listAllFiles($conn);

            foreach ($fileList as $fileItem) {

                $files->setFileID($fileItem['fileID']);
                $files->getAllDetails($conn);
                $member->setUsername($files->getUserID());

                $fileAuthorLink = "../members/view.php?u=" . $files->getUserID();
                $fileViewLink = "view.php?id=" . $files->getFileID();
                $fileEditLink = "edit.php?id=" .  $files->getFileID();

                echo "<tr>";

                echo '<td data-th="File Title">' . $files->getTitle() . '</td>';
                echo '<td data-th="Description">' . $files->getDescription() . '</td>';
                echo '<td data-th="Uploader"><a href="' . $fileAuthorLink . '">' . $member->getFullNameByUsername($conn) . '</a></td>';
                echo '<td data-th="Type">' . $files->displayType() . '</td>';
                echo '<td data-th="Visibility">' . $files->displayVisibility() . '</td>';
                echo '<td data-th="View"><a href="' . $fileViewLink . '">View</a></td>';
                echo '<td data-th="Edit"><a href="' . $fileEditLink . '">Edit</a></td>';

                echo "</tr>";
            }

            echo '</table>';

            dbClose($conn);

            echo '<div class="large-2 large-centered medium-6 medium-centered small-12 small-centered columns">';
            echo '<div class ="row">
            <a href="' . $domain . 'files/upload.php" class="button">Upload File</a>
            </div>
            </div>';
            ?>


    </div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>