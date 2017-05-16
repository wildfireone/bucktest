<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 16/05/2017
 * Time: 20:06
 * Gallery index page - Lists all albums
 */

session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/gallery_albums.obj.php';
require '../obj/gallery_photos.obj.php';

$data = array();
$conn = dbConnect();
$members = new Members();
$album = new gallery_album();
$adding = false;
$photo = new gallery_photos();


$heading = "";
$description = "";

if (isset($_SESSION['username'])) {

    if (!galaFullAccess($connection, $currentUser, $memberValidation)) {
        $albumsList = $album->listAllAlbums($conn);
        $heading = "Gallery | Index";
        $description = "All albums by Bucksburn members";
    } else {
        $adding = true;
        $heading = "Gallery | Index";
        $description = "All albums by Bucksburn members";
        $albumsList = $album->listAllAlbums($conn);
    }
} else {
    $heading = "Gallery | Index";
    $description = "All public Bucksburn gallery albums";
    $albumsList = $album->listAllPublicAlbums($conn);
}

//Display user data in forms
$data['albums'] = $album;
$data['albumList'] = $albumsList;
$data['author'] = $members;
$data['photos'] = $photo;
$data['create'] = $adding;
$data['heading'] = $heading;
$data['description'] = $description;
$data['domain'] = $domain;

//Extract data array to display variables on view template
extract($data);

?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Gallery | Bucksburn Amateur Swimming Club</title>
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
            <li><a href="../gallery/" role="link">Gallery</a></li>
            <li class="current">Index</li>
        </ul>
        <?php

        if (isset($_SESSION['create'])) {
            echo '<br/>';
            echo '<div class="callout success">
          <h5>Album Successfully Added!</h5>
          </div>';
            unset($_SESSION['create']);
        }

        if (isset($_SESSION['delete'])) {
            echo '<br/>';
            echo '<div class="callout alert">
          <h5>Album Successfully Deleted!</h5>
          </div>';
            unset($_SESSION['delete']);
        }


        echo '<h2 class="text-center">' . $heading . '</h2>';
        echo '<p>' . $description . '</p>';

        if ($albumList == null) {
            echo "<p>Sorry there hasn't been any gallery albums added currently. Please come back later.</p>";
        } else {
            echo '<div class="row small-12 medium-up-6 large-up-6">';


            foreach ($albumList as $albums) {
                $album->setAlbumID($albums["albumID"]);
                $album->getAllDetails($conn);
                $author->setUsername($album->getUserID());
                $author->getAllDetails($conn);
                $photos->setAlbumID($album->getAlbumID());
                $photos->getLatestPhoto($conn);

                $albumsLink = $domain . "gallery/view_album.php?a=" . $album->getAlbumID();

                echo '
                <div class="column img_wrap">
                    <h3 class="albumTitle">' . $album->getAlbumName() . '</h3>
                    <a href="' . $albumsLink . '"><img style="width:250px; height:250px;" class="thumbnail" src="' . $photos->getFullFilePath() . '"></a>
                    <p class="img__description"><b>Author:</b> ' . $author->getFullNameByUsername($conn) . '
                    </br><b>Description:</b> ' . $album->getAlbumDescription() . ' 
                    </br><b>Type:</b> ' . $album->displayType() . '
                    </p>
                </div>';
            }
            echo '</div>';
        }

        //If user has editing privileges then display create new album button
        if ($create) {
            echo '<div class="large-2 large-centered medium-6 medium-centered small-12 small-centered columns">';
            echo '<div class ="row">
            <a href="/gallery/create_album.php" class="button">Create new Album</a>
    </div>
    </div>';
        }


        ?>


    </div>


</div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>
