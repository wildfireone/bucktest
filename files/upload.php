<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 05/08/2017
 * Time: 21:11
 * File upload page
 */


session_start();
require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/files.obj.php';


if (isset($_SESSION['username'])) {

    $conn = dbConnect();

    if (!filesFullAccess($conn, $currentUser, $memberValidation)) {

        header('Location:' . $domain . 'login.php');
    }

    if (isset($_POST['btnSubmit'])) {

        $members = new Members($_SESSION['username']);
        $members->getAllDetails($conn);

        $files = new files();

        if (isset($_POST['txtTitle']) && (isset($_POST['txtDescription']))) {

            $files->setUserID($members->getUsername());
            $files->setTitle(htmlentities($_POST['txtTitle']));
            $files->setDescription(htmlentities($_POST['txtDescription']));
            $files->setVisibility(htmlentities($_POST['chkVisibility']));

            if ($files->uploadFile()) {
                echo "file uploaded";
                if ($files->create($conn)) {
                    $_SESSION['upload'] = true;
                    if (isset($_SESSION['addFiles'])) {
                        $pageID = $_SESSION['addFiles'];
                        unset($_SESSION['addFiles']);
                        header('Location:' . $domain . 'pages/edit.php?id='. $pageID);
                    } else {
                        header('Location:' . $domain . 'files');
                    }
                }
            }
        } else {
            $_SESSION['error'] = true;
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
    <title>Upload File| Bucksburn Amateur Swimming Club</title>
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
            <li><a href="../files/" role="link">Files</a></li>
            <li class="current">Upload File</li>
        </ul>
        <h2 class="text-center">Upload File</h2>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<br/>';
            echo '<div class="callout alert">
          <h5>Upload file error!</h5>
          <p>One or more fields was not filled in, please try again!</p>
          </div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="<?php echo htmlentities($_SERVER['REQUEST_URI']); ?>" method="post" style="text-align: center"
              enctype="multipart/form-data">
            <div class="small-6 small-centered large-10 large-centered columns">
                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>File Title</b></span>
                            <input type="text" id="txtTitle" name="txtTitle" maxlength="20"/>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>File Description</b></span>
                            <textarea id="txtDescription" name="txtDescription" rows="2" maxlength="250"></textarea>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 medium-12 small-12 columns">
                        <label>
                            <span><b>Choose a file</b></span>
                            <input type="file" name="fileToUpload" id="fileToUpload">
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
                        <input class="success button" type="submit" name="btnSubmit" value="Add File">
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