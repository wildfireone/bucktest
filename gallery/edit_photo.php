<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 16/05/2017
 * Time: 20:19
 */


session_start();
$data = array();
require '../inc/connection.inc.php';
require '../inc/security.inc.php';


if (isset($_SESSION['username'])) {
    require '../obj/gallery_albums.obj.php';
    require '../obj/gallery_photos.obj.php';

    $conn = dbConnect();

    $photos = new gallery_photos(htmlentities($_GET['p']));
    $photos->getAllDetails($conn);

    $members = new Members($_SESSION['username']);

    $albums = new gallery_album($photos->getAlbumID());
    $albums->getAllDetails($conn);


    if (!$photos->doesExist($conn)) {
        header( 'Location:' . $domain . '404.php' );
    }

    if (isset($_POST['btnSubmit'])) {

        if (isset($_POST['txtTitle']) && (isset($_POST['txtDescription']))) {

            $photos->setTitle(htmlentities($_POST['txtTitle']));
            $photos->setDescription(htmlentities($_POST['txtDescription']));

            if ($photos->update($conn)) {
                $_SESSION['update'] = true;
                header( 'Location:' . $domain . 'gallery/view_photo.php?p='  . $photos->getPhotoID());
                die();
            }
        } else {
            $_SESSION['error'] = true;
        }
    }

    if (isset($_POST['btnDelete'])) {

        $albumID = $photos->getAlbumID();

        //Finally delete photo
        if ($photos->delete($conn)) {
            $_SESSION['deletePhoto'] = true;
            header( 'Location:' . $domain . 'gallery/view_album.php?a='  . $albumID);
        } else {
            $_SESSION['error'] = true;
        }
    }

    //Display user data in forms
    $data['albums'] = $albums;
    $data['author'] = $members;
    $data['photos'] = $photos;

    //Extract data array to display variables on view template
    extract($data);

} else {
    header( 'Location:' . $domain . 'login.php' );
}


?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Edit Photo| Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
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
            <li><a href="../gallery/view_album.php?a=<?=$albums->getAlbumID()?>" role="link">View Album</a></li>
            <li><a href="../gallery/view_photo.php?p=<?=$photos->getPhotoID()?>" role="link">View Photo</a></li>
            <li class="current">Edit Photo</li>
        </ul>

        <?php
        /**
         * Created by PhpStorm.
         * User: Andrew Tait (1504693)
         * Date: 11/03/2017
         * Time: 00:37
         * Edit Photo page
         */

        if (isset($_SESSION['error'])) {
            echo '<br/>';
            echo '<div class="callout warning">
          <h5>Photo Update Failed</h5>
          <p>Form incomplete, errors are highlighted below.</p>
          </div>';
            unset($_SESSION['error']);
        }

        ?>
        <h1 class="pageTitle">Gallery | Edit Photo ID: <?=$photos->getPhotoID()?></h1>

        <form  method="post" style="text-align: center" enctype="multipart/form-data">
            <div class="small-6 small-centered large-10 large-centered columns">
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Photo Title</b></span>
                            <input type="text" id="txtTitle" name="txtTitle" maxlength="20" value="<?=$photos->getTitle()?>"/>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Photo Description</b></span>
                            <textarea id="txtDescription" name="txtDescription" rows="2" maxlength="250"><?=$photos->getDescription()?></textarea>
                        </label>
                    </div>
                </div>
                <div class="large-12 medium-12 small-12 columns">
                    <div class="row">
                        <input class="success button" type="submit" name="btnSubmit" value="Update Photo">
                        <input type="submit" name="btnDelete" class="alert button" value="Delete Photo"
                               onclick="return confirm('Are you sure? This WILL delete this photo')">
                        <input class="button" type="reset" value="Reset">
                    </div>
                </div>


    </div>
        </form>


</div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>