<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 16/05/2017
 * Time: 20:18
 */


session_start();
$data = array();
require '../inc/connection.inc.php';
require '../inc/security.inc.php';


if (isset($_SESSION['username'])) {

    require '../obj/gallery_albums.obj.php';
    require '../obj/gallery_photos.obj.php';

    $conn = dbConnect();


    $albums = new gallery_album();
    $albums->setAlbumID($_GET['a']);
    $albums->getAllDetails($conn);


    if (!$albums->doesExist($conn)) {
        header( 'Location:' . $domain . '404.php' );
    }

    $members = new members($_SESSION['username']);
    $members->getAllDetails($conn);

    $canEdit = false;
    $limitedAccess = true;

    if (isset($_SESSION['username'])) {

        if (galaFullAccess($connection, $currentUser, $memberValidation)) {
            $canEdit = true;
            $limitedAccess = false;
        } else if ($albums->getUserID() == $_SESSION['username']) {
            $canEdit = true;
        }
    }

    if (!$canEdit) {
        header( 'Location:' . $domain . 'gallery' );
    }


    if (isset($_POST['btnSubmit'])) {

        $members = new Members($_SESSION['username']);
        $members->getAllDetails($conn);
        $albums = new gallery_album(($_GET['a']));
        $albums->getAllDetails($conn);
        if (isset($_POST['txtName']) && (isset($_POST['txtDescription']))) {
            $albums->setAlbumName(htmlentities($_POST['txtName']));
            $albums->setAlbumDescription(htmlentities($_POST['txtDescription']));

            if (!$limitedAccess) {
                $albums->setVisibility(htmlentities($_POST['chkVisibility']));
            }
            $albums->setType(htmlentities($_POST['sltType']));


            if ($albums->update($conn)) {
                $_SESSION['update'] = true;
                header( 'Location:' . $domain . 'gallery/view_album.php?a=' . $albums->getAlbumID() );
            }
        } else {
            $_SESSION['error'] = true;
        }
    }

    if (isset($_POST['btnDelete'])) {

        $albums = new gallery_album(htmlentities(($_GET['a'])));
        $albums->getAllDetails($conn);
        $photos = new gallery_photos();
        $photos->setAlbumID($albums->getAlbumID());


        //Delete all photos from the album
        if ($photos->delete($conn, $albums->getAlbumID())) {
            //Finally delete album
            if ($albums->delete($conn)) {
                $_SESSION['delete'] = true;
                header( 'Location:' . $domain . 'gallery' );
            }
        } else {
            $_SESSION['error'] = true;
        }
    }

    //Display user data in forms
    $data['albums'] = $albums;
    $data['author'] = $members;
    $data['limitedAccess'] = $limitedAccess;

    //Extract data array to display variables on view template
    extract($data);

} else {
    header('Location:' .$domain . 'login.php');
}


?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Edit Album| Bucksburn Amateur Swimming Club</title>
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
            <li class="current">Edit Album</li>
        </ul>

        <?php

        if (isset($_SESSION['error'])) {
            echo '<br/>';
            echo '<div class="callout warning">
          <h5>Profile Update Failed</h5>
          <p>Form incomplete, errors are highlighted below.</p>
          </div>';
            unset($_SESSION['error']);
        }

        ?>
        <h2 class="text-center" >Gallery | Edit album: <?=$albums->getAlbumName()?></h2>

        <div class="small-6 small-centered large-10 large-centered columns">
            <form action="" method="post">
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Album Name</b></span>
                            <input type="text" id="txtName" name="txtName" maxlength="20" value="<?=$albums->getAlbumName()?>"/>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Album Description</b></span>
                            <textarea id="txtDescription" name="txtDescription" rows="4" maxlength="250"><?=$albums->getAlbumDescription()?></textarea>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label><b>
                                <span class="required">* </span>Type</b>
                            <select id="sltType" name="sltType">
                                <option value="1" <?= ($albums->getType() == 1) ? "selected" : ""; ?> >Standard</option>
                                <option value="2" <?= ($albums->getType() == 2) ? "selected" : ""; ?>  >Personal</option>
                                <option value="3" <?= ($albums->getType() == 3) ? "selected" : ""; ?> >Competitions</option>
                                <option value="4" <?= ($albums->getType() == 4) ? "selected" : ""; ?> >Events</option>
                            </select>
                        </label>
                    </div>
                </div>


                <?php
                if(!$limitedAccess)
                {
                    ?>
                    <div class="row">
                        <div class="large-12 medium-12 small-12 columns">
                            <label><b>
                                    <span class="required">* </span>Visibility (Publicly accessible?)</b>
                                <input type='hidden' value='0' name='chkVisibility'/>
                                <input id="chkVisibility" type="checkbox" name="chkVisibility" value="1"  <?php echo($albums->getVisibility() == 1 ? 'checked' : ''); ?>/>

                            </label>
                        </div>
                    </div>
                <?php }?>

                <div class="large-12 medium-12 small-12 columns">
                    <div class="row">
                        <input class="success button" type="submit" name="btnSubmit" value="Update Album">
                        <input type="submit" name="btnDelete" class="alert button" value="Delete Album"
                               onclick="return confirm('Are you sure? This WILL delete this album and all the photos in it.')">
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