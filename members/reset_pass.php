<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 14/05/2017
 * Time: 17:47
 */


session_start();

require '../inc/connection.inc.php';
require '../inc/security.inc.php';
require_once '../obj/members.obj.php';
require_once '../obj/members_roles.obj.php';

$limited_edit = false;

if (!isset($_SESSION['username'])) {
    header('Location:' . $domain . 'message.php?id=badaccess');
    exit;
}

// Check for a parameter before we send the header

    $connection = dbConnect();
    $members = new Members($_SESSION["username"]);
    if (!$members->doesExist($connection)) {
        header('Location:' . $domain . '404.php');
        exit;
    }


if (isset($_POST['btnSubmit'])) {

    $connection = dbConnect();
    $member_Validation = new Members($_SESSION["username"]);

    $members_rolesValidation = new Members_Roles();


    if ($_POST["txtPassword"] == $_POST["txtPasswordConfirm"]) {
        {
            $errors = array();
            $member_Validation->checkPasswordReset($connection,$_POST['txtPassword'], $errors);

            if (sizeof($errors) <= 0) {
                if ($member_Validation->updatePassword($connection, $_POST['txtPassword'])) {

                    $member_Validation->setReset(0);
                    if ($member_Validation->updateResetFlag($connection)) {
                        $_SESSION['updatePassword'] = true;
                    } else {
                        $_SESSION['updatePassword'] = false;
                    }

                    if ($_SESSION['updatePassword']) {

                        header('Location:' . $domain . 'members/view.php?u=' . $member_Validation->getUsername());
                        die();
                    }
                } else {
                    $_SESSION['error'] = true;
                }
            } else {
                $_SESSION['failed'] = true;
            }

        }
    } else {
        $_SESSION['invalid'] = true;
    }

}
?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include '../inc/meta.inc.php'; ?>
    <title>Edit | Members | Bucksburn Amateur Swimming Club</title>
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
            <li><a href="../members.php" role="link">Members</a></li>
            <li class="current">Edit a Member</li>
        </ul>

        <h2>Password Reset</h2>
        <p>You are required to reset your account password before proceeding</p>
        The password <b>must</b> meet the following conditions:
        <ul>
            <li>Password must be at least 8 characters in length</li>
            <li>Password must contain at least one letter</li>
            <li>Password must contain at least one number</li>
        </ul>

        <?php
        require '../inc/forms.inc.php';
        require '../obj/status.obj.php';
        require '../obj/roles.obj.php';
        require '../obj/squads.obj.php';

        $conn = dbConnect();

        if (isset($_SESSION['invalid'])) {
            echo '<p class="alert-box error radius centre">Passwords do not match, please try again.</p>';
            unset($_SESSION['invalid']);
        }

        if (isset($_SESSION['failed'])) {
            echo '<p class="alert-box error radius centre">Password failed complexity checks, please see below:</p>';
            echo '<ul>';
            foreach ($errors as $value) {
                echo '<li>' . $value . '</li>';
            }
            echo '</ul>';
            unset($_SESSION['failed']);
        }

        if (isset($_SESSION['error'])) {
            echo '<p class="alert-box error radius centre">There was an error updating the members password. Please try again.</p>';
            unset($_SESSION['error']);
        }

        $status = New Status();
        $roles = New Roles();
        $squads = New Squads();
        $members_roles = New Members_Roles();

        $member = new Members($_SESSION["username"]);
        $member->getAllDetails($conn);

        echo formStart();

        echo '<div class="large-6 medium-6 small-12 middle center"><fieldset><legend>Update Password</legend>';


        echo textInputSetup(false, "Username", "txtUsername", $member->getUsername(), 8, true);

        if (isset($_POST["btnSubmit"])) {
            echo passwordInputEmptyError(true, "Password", "txtPassword", "errEmptyPassword", "Please enter a Password", 32);
        } else {
            echo passwordInputBlank(true, "Password", "txtPassword", 32);
        }

        echo passwordInputBlank(true, "Confirm Password", "txtPasswordConfirm", 32);



        echo formEndWithButton("Save Changes");


        dbClose($conn);
        ?>

    </div>
</div>
</div>
<?php include '../inc/footer.inc.php'; ?>
</body>

</html>
