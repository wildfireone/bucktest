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


if (isset($_SESSION['username'])) {
    require '../obj/gallery_albums.obj.php';
    require '../obj/gallery_photos.obj.php';
    $conn = dbConnect();

    //If create new button is submitted
    if (isset($_POST['btnSubmit'])) {

        $member = new Members($_SESSION['username']);
        $member->getAllDetails($conn);
        $albums = new gallery_album();

        if (isset($_POST['txtName']) && (isset($_POST['txtDescription']))) {
            $albums->setAlbumName(htmlentities($_POST['txtName']));
            $albums->setAlbumDescription(htmlentities($_POST['txtDescription']));
            $albums->setVisibility(htmlentities($_POST['chkVisibility']));
            $albums->setType(htmlentities($_POST['sltType']));
            $albums->setUserID($member->getUsername());

            if ($albums->create($conn)) {
                $_SESSION['create'] = true;
                header('Location:' .$domain . 'gallery');
            }
        } else {
            $_SESSION['error'] = true;
        }
    }

} else {
    header('Location:' .$domain . '/login.php');
}



?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Create Album| Bucksburn Amateur Swimming Club</title>
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
        <div class="small-6 small-centered large-10 large-centered columns">
            <form action="" method="post">
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Album Name</b></span>
                            <input type="text" id="txtName" name="txtName" maxlength="50"/>
                        </label>
                    </div>
                </div>


                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Album Description</b></span>
                            <textarea id="txtDescription" name="txtDescription" rows="4" maxlength="250"></textarea>
                        </label>
                    </div>
                </div>


                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label><b>
                                <span class="required">* </span>Type</b>
                            <select id="sltType" name="sltType">
                                <option value="" selected="selected">Please select...</option>
                                <option value="1">Standard</option>
                                <option value="2">Personal</option>
                                <option value="3">Competitions</option>
                                <option value="4">Events</option>
                            </select>
                        </label>
                    </div>
                </div>


                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label><b>
                                <span class="required">* </span>Visibility (Publicly accessible?)</b>
                            <input type='hidden' value='0' name='chkVisibility'/>
                            <input id="chkApproved" type="checkbox" name="chkVisibility" value="1"/>
                        </label>
                    </div>
                </div>

                <div class="large-12 medium-12 small-12 columns">
                    <div class="row">
                        <input class="success button" type="submit" name="btnSubmit" value="Add Album">
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
