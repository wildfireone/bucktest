<?php
ob_start();
session_start();
require 'inc/connection.inc.php';
require 'inc/security.inc.php';
require 'inc/functions.inc.php';
require_once 'obj/members.obj.php';

if(isset($_SESSION['username'])){
    header('location: ' . $domain . 'member-area.php');
    die();
}
if (isset($_POST['btnSubmit'])) {
    if (empty($_POST['txtUsername']) || empty($_POST['txtPassword'])) {
        $_SESSION['error'] = true;
    } else {

        $conn = dbConnect();
        if (login($_POST['txtUsername'], $_POST['txtPassword'], $conn)) {
            $_SESSION['username'] = $_POST['txtUsername'];
            $member = new Members($_SESSION['username']);
            $member->getAllDetails($conn);
            // update last login time
            $_SESSION['lastLogin'] = $member->getLastLoginDate();
            $member->updateLastLogin($conn);
            $_SESSION['firstName'] = $member->getFirstName();

            header('Location: ' . $domain . 'member-area.php');
            die();
        } else {
            $_SESSION['error'] = true;
        }
        dbClose($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include 'inc/meta.inc.php';?>
    <title>Login | Bucksburn Amateur Swimming Club</title>
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <link href="css/site.css" rel="stylesheet"/>
</head>

<body>
<?php include 'inc/header.inc.php'; ?>
<br>
<div class="row" id="content">
    <div class="large-12 medium-12 small-12 columns">

        <ul class="breadcrumbs">
            <li><a href="index.php" role="link">Home</a></li>
            <li class="current">Login</li>
        </ul>

        <h2>Login</h2>

        <div class="large-6 large-centered medium-9 medium-centered small-12 columns">
            <?php
            require 'inc/forms.inc.php';

            if (isset($_SESSION['error'])) {
                echo '<p class="alert-box alert radius centre">The username and/or password you entered was incorrect.</p>';
                unset($_SESSION['error']);

                echo formStart();
                echo textInputEmptyError(true, "Username", "txtUsername", "errUsername", "", 8);
                echo passwordInputEmptyError(true, "Password", "txtPassword", "errPassword", "", 50);
                echo formEndWithButton("Login");
            } else {
                echo formStart();
                echo textInputBlank(true, "Username", "txtUsername", 8);
                echo passwordInputBlank(true, "Password", "txtPassword", 50);
                echo formEndWithButton("Login");
            }
            ?>
        </div>
    </div>
</div>
<?php include 'inc/footer.inc.php';?>
</body>

</html>
