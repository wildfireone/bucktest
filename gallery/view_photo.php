<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 16/05/2017
 * Time: 20:18
 */



session_start();
require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/gallery_albums.obj.php';
require '../obj/gallery_photos.obj.php';

$data = array();
$conn = dbConnect();
$photos = new gallery_photos();
$photos->setPhotoID($_GET['p']);
$photos->getAllDetails($conn);


if (!$photos->doesExist($conn)) {
    header('Location:' .$domain . '404.php');
}


$members = new Members($photos->getUserID());
$albums = new gallery_album($photos->getAlbumID());
$albums->getAllDetails($conn);


if (!isset($_SESSION['username']) && $albums->getVisibility() == 0) {
    header('Location:' .$domain . 'login.php');
}

$members->getAllDetails($conn);

$canEdit = false;

if (isset($_SESSION['username'])) {

    if (galleryFullAccess($connection, $currentUser, $memberValidation)) {
        $canEdit = true;
    } else if ($members->getUserName() == $_SESSION['username']) {
        $canEdit = true;
    }
}


$data['albums'] = $albums;
$data['photos'] = $photos;
$data['author'] = $members;
$data['edit'] = $canEdit;

//Extract data array to display variables on view template
extract($data);



?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>View Photo| Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
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
            <li class="current">View Photo</li>
        </ul>
        <?php
        if (isset($_SESSION['update'])) {
            echo '<div class="alert-box success">
          <p>Photo Successfully Updated!</p>
          </div>';
            unset($_SESSION['update']);
        }
        ?>

        <h1 class="pageTitle" >Gallery | View photo: <?=$photos->getTitle()?> </h1>
        <div class="small-6 small-centered large-10 large-centered columns">
            <div class="row ">
                <div class="separator"></div>
                <div class ="highslide-gallery small-6 small-centered columns">
                    <a id="thumb1" href="<?=$domain.$photos->getFilePath()?>" class ="highslide" onclick="return hs.expand(this)">
                        <img class="displayed" src="<?=$domain.$photos->getFilePath()?>" alt="Highslide JS"
                             title="Click to enlarge" />
                    </a>
                    <div class="highslide-caption">
                        Title: <?=$photos->getTitle()?> <br/>
                        Description: <?=$photos->getDescription()?> <br/>
                        <b><a href="<?=$domain.$photos->getFilePath()?>">View the Photo</a></b>
                    </div>
                </div>
            </div>

            <div class="row">
                <h4 align="center"> Photo details:
                    <?php
                    if ($edit) {
                        echo ' <a href="'.$domain.'gallery/edit_photo.php?p=' . $photos->getPhotoID() . '" class="button">[Edit]</a>';
                    }
                    ?>
                </h4>
                <table style="width: 80%; margin-right:auto; margin-left: auto">
                    <tr>
                        <th>Photo Description:</th>
                        <td><?=$photos->getDescription()?></td>
                    </tr>
                    <tr>
                        <th>Album Title:</th>
                        <td><?=$albums->getAlbumName()?></td>

                    </tr>
                    <tr>
                        <th>Album Description:</th>
                        <td><?=$albums->getAlbumDescription()?></td>

                    </tr>
                    <tr>
                        <th>Author:</th>
                        <td><?=$author->getFullNameByUsername($conn)?></td>
                    </tr>
                    <tr>
                        <th>Created Date:</th>
                        <td><?=$photos->getCreatedDate()?></td>
                    </tr>
                    <tr>
                        <th>Last Modified Date</th>
                        <td><?=$photos->getModifiedDate()?></td>
                    </tr>
                </table>
            </div>

            <div class="row ">
                <div class="separator"></div>
                <div class ="small-centered columns">
                    <a href="<?=$domain?>gallery/view_album.php?a=<?=$albums->getAlbumID()?>" class="button">Return to album</a>
                </div>
            </div>

        </div>


    </div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>