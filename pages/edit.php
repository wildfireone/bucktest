<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 03/08/2017
 * Time: 21:16
 */

session_start();
// Check for a parameter before we send the header
require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/files.obj.php';

if (!isset($_SESSION['username'])) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}

if (!pagesFullAccess($connection, $currentUser, $memberValidation)) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}


// Check for a parameter before we send the header
if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {
    header('Location:' . $domain . '404.php');
    exit;
} else {
    $connection = dbConnect();
    $pages = new pages($_GET["id"]);
    if (!$pages->doesExist($connection)) {
        header('Location:' . $domain . '404.php');
        exit;
    }
}

if (isset($_POST['btnSubmit'])) {

    $connection = dbConnect();
    $pages = new pages($_GET["id"]);
    $member = new Members($_SESSION['username']);
    $member->getAllDetails($connection);

    if ($pages->isInputValid($_POST['txtTitle'], $_POST['txtDescription'], $_POST['txtMainBody'])) {
        $pages->setPageTitle($_POST['txtTitle']);
        $pages->setPageDescription($_POST['txtDescription']);
        $pages->setPageContent($_POST['txtMainBody']);
        $pages->setVisibility(htmlentities($_POST['chkVisibility']));

        if ($pages->update($connection)) {
            $_SESSION['update'] = true;


            if (isset($_SESSION['editDescription'])) {
                $pageID = $_SESSION['editDescription'];
                unset($_SESSION['editDescription']);
                header('Location:' . $domain . $pageID);
            } else {
                header('Location:' . $domain . 'pages/view.php?id=' . $pages->getPageID());
                die();
            }

        } else {
            $_SESSION['error'] = true;
        }
    } else {
        $_SESSION['invalid'] = true;
    }
    dbClose($connection);
}

if (isset($_POST['btnAddFiles'])) {
    $_SESSION['addFiles'] = $pages->getPageID();
    header('Location:' . $domain . 'files/upload.php');
    die();
}


?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Edit | Page | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <script src='../tinymce/tinymce.min.js'></script>
    <script>
        tinymce.init({
            selector: '#txtMainBody',
            plugins: 'advlist, table, autolink, code, contextmenu, imagetools, fullscreen, hr,  colorpicker, preview, spellchecker, link, autosave, lists, visualblocks'
        });
    </script>
</head>

<body>
<?php include '../inc/header.inc.php'; ?>
<br>

<div class="row" id="content">
    <div class="large-12 medium-12 small-12 columns">

        <ul class="breadcrumbs">
            <li><a href="../index.php" role="link">Home</a></li>
            <li><a href="/pages/view.php?id=<?php echo $pages->getPageID(); ?>" role="link">Page</a></li>
            <li class="current">Edit a Page Item</li>
        </ul>

        <h2>Edit a Page Item


            <?php
            require '../inc/forms.inc.php';


            $conn = dbConnect();

            if (isset($_SESSION['invalid'])) {
                echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                unset($_SESSION['invalid']);
            }
            if (isset($_SESSION['error'])) {
                echo '<p class="alert-box error radius centre">There was an error creating the page item. Please try again.</p>';
                unset($_SESSION['error']);
            }

            echo formStart();

            $pages = new pages($_GET["id"]);
            $pages->getAllDetails($conn);

            if (isset($_POST["btnSubmit"])) {
                if (empty($_POST["txtTitle"])) {
                    echo textInputEmptyError(true, "Page Title", "txtTitle", "errEmptyTitle", "Please enter a Page Title", 250);
                } else {
                    echo textInputPostback(true, "Page Title", "txtTitle", $_POST["txtTitle"], 250);
                }
            } else {
                echo textInputSetup(true, "Page Title", "txtTitle", $pages->getPageTitle(), 250);
            }

            if (isset($_POST["btnSubmit"])) {
                echo textInputPostback(false, "Page Description", "txtDescription", $_POST["txtSubTitle"], 250);
            } else {
                echo textInputSetup(false, "Page Description", "txtDescription", $pages->getPageDescription(), 250);
            }
            ?>
            <div class="row">
                <div class="large-12 medium-12 small-12 columns">
                    <label><b>
                            <span class="required">* </span>Visibility (Publicly accessible?)</b>
                        <input type='hidden' value='0' name='chkVisibility'/>
                        <input id="chkVisibility" type="checkbox" name="chkVisibility"
                               value="1" <?php echo($pages->getVisibility() == 1 ? 'checked' : ''); ?>/>

                    </label>
                </div>
            </div>
            <?php
            if (isset($_POST["btnSubmit"])) {
                if (empty($_POST["txtMainBody"])) {
                    echo textareaInputEmptyError(true, "Main Body", "txtMainBody", "errEmptyBody", "Please enter a Main Body", 100000, 20);
                } else {
                    echo textareaInputPostback(true, "Main Body", "txtMainBody", $_POST["txtMainBody"], 100000, 20);
                }
            } else {
                echo textareaInputSetup(true, "Main Body", "txtMainBody", $pages->getPageContent(), 100000, 20);
            }
            ?>

            <br/>
            <br/>
            <h3 class="centre">Add Files to page <input name="btnAddFiles" value="Upload File" class="button"
                                                        type="submit"></h3>


            <div id="FileTable">
                <table class="large-12 medium-12 small-12 columns fileTable" style=" overflow-y: scroll;">
                    <tr>
                        <th>File Title</th>
                        <th>File Description</th>
                        <th>Uploader</th>
                        <th>Type</th>
                        <th>Visibility</th>
                        <th>View</th>
                        <th>Add</th>
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
                        $fileViewLink = "../" . "files/view.php?id=" . $files->getFileID();
                        $fileDirectLink = "../" . $files->getFilePath();

                        echo "<tr>";

                        echo '<td data-th="File Title">' . $files->getTitle() . '</td>';
                        echo '<td data-th="Description">' . $files->getDescription() . '</td>';
                        echo '<td data-th="Uploader"><a href="' . $fileAuthorLink . '">' . $member->getFullNameByUsername($conn) . '</a></td>';
                        echo '<td data-th="Type">' . $files->displayType() . '</td>';
                        echo '<td data-th="Visibility">' . $files->displayVisibility() . '</td>';
                        echo '<td data-th="View"><a href="' . $fileViewLink . '">View</a></td>';

                        if ($files->getType() == 0) {
                            echo '<td data-th="View"><button type="button" data-value0="0" data-value1="' . $fileDirectLink . '">Add inline</button></td>';
                        } else {
                            echo '<td data-th="View"><button type="button" data-value0="1"  data-value1="' . $fileViewLink . '">Add inline</button></td>';
                        }


                        echo "</tr>";
                    }

                    echo '</table>';
                    echo '</div>';

                    echo formEndWithButton("Save changes", "delete.php?id=" . $pages->getPageID());


                    ?>


            </div>
    </div>

    <?php include '../inc/footer.inc.php'; ?>
    <script src="<?php echo $domain ?>/js/files.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>
