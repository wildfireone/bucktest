<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 16/05/2017
 * Time: 20:18
 * View individual album based on ID
 */


session_start();
require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/gallery_albums.obj.php';
require '../obj/gallery_photos.obj.php';

$data = array();
$conn = dbConnect();

$albums = new gallery_album();
$albums->setAlbumID($_GET['a']);
$albums->getAllDetails($conn);

if (!$albums->doesExist($conn)) {
    header( 'Location:' . $domain . '404.php' );
}

if (!isset($_SESSION['username']) && $albums->getVisibility() == 0) {
    header('Location:' .$domain . '/login.php');
}

$members = new members($albums->getUserID());
$members->getAllDetails($conn);

$photos = new gallery_photos();
$photos->setAlbumID($albums->getAlbumID());
$photo_listing = $photos->listPhotoAlbum($conn);

$canEdit = false;

if (isset($_SESSION['username'])) {

    if (galleryFullAccess($connection, $currentUser, $memberValidation)) {
        $canEdit = true;
    } else if ($members->getUsername() == $_SESSION['username']) {
        $canEdit = true;
    }
}


//Display user data in forms

$data['albums'] = $albums;
$data['photos'] = $photos;
$data['photoList'] = $photo_listing;
$data['author'] = $members;
$data['edit'] = $canEdit;


//Extract data array to display variables on view template
extract($data);


?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>View Album| Bucksburn Amateur Swimming Club</title>
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
            <li><a href="../gallery/index.php" role="link">Gallery</a></li>
            <li class="current">View Album</li>
        </ul>
        <?php

        if (isset($_SESSION['uploads'])) {
            echo '<br/>';
            echo '<div class="callout success">
          <h5>Photo Successfully uploaded!</h5>
          </div>';
            unset($_SESSION['uploads']);
        }

        if (isset($_SESSION['deletePhoto'])) {
            echo '<br/>';
            echo '<div class="callout alert">
          <h5>Photo Successfully Deleted!</h5>
          </div>';
            unset($_SESSION['deletePhoto']);
        }

        if (isset($_SESSION['update'])) {
            echo '<br/>';
            echo '<div class="callout success">
          <h5>Album Successfully Updated!</h5>
          </div>';
            unset($_SESSION['update']);
        }


        echo '<h2 class="text-center" >Gallery | ' . $albums->getAlbumName() . ' </h2>';

        if ($photoList == null) {
            echo "<p>Sorry no photos are added to this album at the moment. Please come back later.</p>";
        } else {

            echo '<div class="row small-12 medium-up-4 large-up-4 highslide">';

            foreach ($photoList as $photos) {
                $photo = new gallery_photos();
                $photo->setPhotoID($photos["photoID"]);
                $photo->getAllDetails($conn);
                $author->setUserName($photo->getUserID());
                $author->getAllDetails($conn);
                $photosLink = $_SESSION['domain'] . "gallery/view_photo.php?p=" . $photo->getPhotoID();
                $photoFileLink = $_SESSION['domain'] . $photo->getFullFilePath();

                echo '
        <div class="column">
        <a href="' . $photo->getFullFilePath() . '" class="highslide" onclick="return hs.expand(this)">
                <img style="width:260px; height:260px;" class="thumbnail" src="' . $photo->getFullFilePath() . '" alt="Highslide JS"
                     title="Click to enlarge" />
            </a>
            
            <div class="highslide-caption">
            <b>Title:</b> ' . $photo->getTitle() . '<br>
            <b>Description:</b> ' . $photo->getDescription() . '<br>
             <b>By:</b>' . $author->getFullNameByUsername($conn) . ' <br>
            <b><a href="' . $photosLink . '">View the Photo</a></b>
            </div>

        </div>
    ';
            }
            echo '</div>';
        }

        if ($edit) {
            echo '<div class="large-2 large-centered medium-6 medium-centered small-12 small-centered columns">
    <div class="row">
        <a href="/gallery/upload.php" class="button">Upload Photos</a>
    </div>
    </div>';
            $_SESSION['albumUpload'] = $albums->getAlbumID();
        }

        ?>
        <div class="row">
            <h4 align="center"> Album Details:
                <?php
                if ($edit) {
                    echo ' <a href="/gallery/edit_album.php?a=' . $albums->getAlbumID() . '" class="button">[Edit]</a>';
                }
                ?>
            </h4>
            <table style="width: 80%; margin-right:auto; margin-left: auto">
                <tr>
                    <th>Album Title:</th>
                    <td><?= $albums->getAlbumName() ?></td>
                </tr>
                <tr>
                    <th>Album Description:</th>
                    <td><?= $albums->getAlbumDescription() ?></td>
                </tr>
                <tr>
                    <th>Album Type:</th>
                    <td><?= $albums->displayType() ?></td>
                </tr>
                <tr>
                    <th>Author:</th>
                    <td><?= $author->getFullNameByUsername($conn) ?></td>
                </tr>
                <tr>
                    <th>Created Date:</th>
                    <td><?= $albums->getCreatedDate() ?></td>
                </tr>
                <tr>
                    <th>Last Modified Date</th>
                    <td><?= $albums->getModifiedDate() ?></td>
                </tr>
            </table>
        </div>

        <div class="row ">
            <div class="separator"></div>
            <div class="small-centered columns">
                <a href="/gallery/" class="button">Return to gallery</a>
            </div>
        </div>

    </div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>