<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 03/08/2017
 * Time: 22:07
 * Pages index view
 */

session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require '../obj/pages.obj.php';

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
    <title>Pages | Bucksburn Amateur Swimming Club</title>
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet"/>
</head>

<body>
<?php include '../inc/header.inc.php'; ?>
<br>
<div class="row" id="content">
    <div class="row-12 columns">

        <ul class="breadcrumbs">
            <li><a href="index.php" role="link">Home</a></li>
            <li class="current">Pages</li>
        </ul>

        <h2>Pages List</h2>

        <?php
        if (isset($_SESSION['delete'])) {
            echo '<p class="alert-box success radius centre">Page deleted successfully!</p>';
            unset($_SESSION['delete']);
        }
        ?>

        <table class="large-12 medium-12 small-12 columns">
            <tr>
                <th>Page Title</th>
                <th>Page Description</th>
                <th>Author</th>
                <th>Created</th>
                <th>Modified</th>
                <th>Visibility</th>
                <th>View</th>
                <th>Edit</th>
            </tr>
            <?php
            require_once '../obj/members.obj.php';

            $conn = dbConnect();

            $pages = new pages();
            $member = new Members();
            $pageList = $pages->listAllPages($conn);

            foreach ($pageList as $pageItem) {

                $pages->setPageID($pageItem['pageID']);
                $pages->getAllDetails($conn);
                $member->setUsername($pages->getUserID());

                //set hyperlink to point to member search with squadID parameter
                $pageAuthorLink = "../members/view.php?u=" . $pages->getUserID();
                $pageViewLink = "view.php?id=" . $pages->getPageID();
                $pageEditLink = "edit.php?id=" . $pages->getPageID();

                echo "<tr>";

                echo '<td data-th="Page Title">' . $pages->getPageTitle() . '</td>';
                echo '<td data-th="Description">' . $pages->getPageDescription() . '</td>';
                echo '<td data-th="Author"><a href="' . $pageAuthorLink . '">' . $member->getFullNameByUsername($conn) . '</a></td>';
                echo '<td data-th="Created">' . $pages->getCreatedDate() . '</td>';
                echo '<td data-th="Modified">' . $pages->getModifiedDate() . '</td>';
                echo '<td data-th="Visibility">' . $pages->displayVisiblity() . '</td>';
                echo '<td data-th="View"><a href="' . $pageViewLink . '">View</a></td>';
                echo '<td data-th="Edit"><a href="' . $pageEditLink . '">Edit</a></td>';

                echo "</tr>";
            }

            echo '</table>';

            dbClose($conn);

            echo '<div class="large-2 large-centered medium-6 medium-centered small-12 small-centered columns">';
            echo '<div class ="row">
            <a href="' . $domain . 'pages/create.php" class="button">Create new Page</a>
    </div>
    </div>';
            ?>


    </div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>