<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 07/11/2016
 * Time: 01:05
 */
session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require_once '../obj/members.obj.php';
require_once '../obj/members_roles.obj.php';


if (!isset($_SESSION['username'])) {
    header( 'Location:' . $domain . 'message.php?id=badaccess' );
    exit;
}


// Check for a parameter before we send the header
if (is_null($_GET["u"])) {
    header( 'Location:' . $domain . '404.php' );
    exit;
} else {
    $connection = dbConnect();
    $members = new Members($_GET["u"]);
    if (!$members->doesExist($connection)) {
        header( 'Location:' . $domain . '404.php' );
        exit;
    }

    //No Full access? Show 403 error message
    if(!memberFullAccess($connection,$currentUser,$memberValidation)) {
        if($_SESSION['username'] == $_GET["u"]){
            header( 'Location:' . $domain . 'message.php?id=badaccess' );
            exit;
        }
        else
        {
            header( 'Location:' . $domain . 'message.php?id=badaccess' );
            exit;
        }
    }
}

    if (isset($_POST['btnSubmit'])) {

        $connection = dbConnect();
        $member_Validation = new Members($_GET["u"]);
        $members_rolesValidation = new Members_Roles();

        if($member_Validation->delete($connection) && $members_rolesValidation->delete($connection, $member_Validation->getUsername()))
        {
            $_SESSION['delete'] = true;
            header('Location:' .$domain . 'members.php');
            die();
        } else {
            $_SESSION['error'] = true;
        }

}
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php';?>
    <title>Edit | Members | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <link href="../css/site.css" rel="stylesheet"/>
    </head>

    <body>
<?php include '../inc/header.inc.php';?>
    <br>

    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">

            <ul class="breadcrumbs">
                <li><a href="../index.php" role="link">Home</a></li>
                <li><a href="../members.php" role="link">Members</a></li>
                <li class="current">Delete a Member</li>
            </ul>

            <h2>Delete a Member</h2>

<?php
                require '../inc/forms.inc.php';

                $conn = dbConnect();

                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the member. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                $member = new Members($_GET["u"]);
                $member->getAllDetails($conn);

                echo formStart(false);

                echo '<div class="panel"><p class="centre middle">Are you sure you want to delete the Member <b>' . $member->getUsername() . '</b>?</p></div>';

                echo formEndWithDeleteButton("Delete");

                dbClose($conn);
                ?>

        </div>
    </div>
<?php include '../inc/footer.inc.php';?>
    </body>

</html>