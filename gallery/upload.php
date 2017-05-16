<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 16/05/2017
 * Time: 20:19
 * Upload Photo to gallery page
 */


session_start();
require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/gallery_albums.obj.php';
require '../obj/gallery_photos.obj.php';


if (isset($_SESSION['username'])) {

    $conn = dbConnect();
    $album = new gallery_album();


    if (galleryFullAccess($connection, $currentUser, $memberValidation)) {
        //Standard members can uploads to their own albums only.
        $albumsList = $album->listAllAlbumSelect($conn, $_SESSION['username']);

    } else {
        //Admins can uploads to any album
        $albumsList = $album->listAllAlbumSelect($conn);
    }

    if (isset($_POST['btnSubmit'])) {

        $members = new Members($_SESSION['username']);
        $members->getAllDetails($conn);

        $photos = new gallery_photos();

        if (isset($_POST['txtTitle']) && (isset($_POST['txtDescription']))) {


            $photos->setAlbumID(htmlentities($_POST['sltAlbum']));
            $photos->setUserID($members->getUsername());
            $photos->setTitle(htmlentities($_POST['txtTitle']));
            $photos->setDescription(htmlentities($_POST['txtDescription']));


            if ($photos->uploadPhoto()) {
                if ($photos->create($conn)) {
                    $_SESSION['uploads'] = true;
                    header( 'Location:' . $domain . 'gallery/view_album.php?a=' . $photos->getAlbumID());
                }
            }
        } else {
            $_SESSION['error'] = true;
        }
    }

    //Display user data in forms
    $data['albums'] = $album;
    $data['albumList'] = $albumsList;

    //Extract data array to display variables on view template
    extract($data);


} else {
    header('Location:' .$domain . '/login.php');
}



?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Upload Photo| Bucksburn Amateur Swimming Club</title>
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
            <li class="current">Create Album</li>
        </ul>
        <h2 class="text-center">Create Album</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<br/>';
            echo '<div class="callout alert">
          <h5>Create album error!</h5>
          <p>One or more fields was not filled in, please try again!</p>
          </div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>" method="post" style="text-align: center" enctype="multipart/form-data">
        <div class="small-6 small-centered large-10 large-centered columns">
            <div class="row">
                <div class="large-12 medium-12 small-12 columns">
                    <label><b>Choose an album to upload to:</b></span>
                        <?php

                        echo '<select id="sltAlbum" name="sltAlbum">';
                        foreach ($albumList as $key => $value) {

                            if (isset($_SESSION['albumUpload']) && $key == $_SESSION['albumUpload']) {
                                echo '<option selected  value="' . $key . '">' . $value . '</option>';
                                unset($_SESSION['albumUpload']);
                            } else {
                                echo '<option value="' . $key . '">' . $value . '</option>';
                            }
                        }
                        echo '</select>';
                        ?>
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="large-12 medium-12 small-12 columns">
                    <label>
                        <span><b>Photo Title</b></span>
                        <input type="text" id="txtTitle" name="txtTitle" maxlength="20"/>
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="large-12 medium-12 small-12 columns">
                    <label>
                        <span><b>Photo Description</b></span>
                        <textarea id="txtDescription" name="txtDescription" rows="2" maxlength="250"></textarea>
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="large-12 medium-12 small-12 columns">
                    <label>
                        <span><b>Choose a photo</b></span>
                        <input type="file" name="fileToUpload" id="fileToUpload">
                    </label>
                </div>
            </div>

            <div class="large-12 medium-12 small-12 columns">
                <div class="row">
                    <input class="success button" type="submit" name="btnSubmit" value="Add Photo">
                    <input class="button" type="reset" value="Reset">
                </div>
            </div>

            </form>
        </div>

</div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>