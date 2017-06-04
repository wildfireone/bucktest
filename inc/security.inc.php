<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait
 * Date: 26/09/2016
 * Time: 17:22
 * Security file that is included on all pages to check if user has access to certain areas of the website.
 **/

//Debugging stuff
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
ob_start();
$domain = 'http://bucktest.dev/';
$_SESSION['domain'] = $domain;

//Switch include paths when on root or sub (sub) folder
if (file_exists('obj/members.obj.php')) {
    require_once 'obj/members.obj.php';

} elseif (file_exists('../obj/members.obj.php')) {
    require_once '../obj/members.obj.php';
} elseif (file_exists('../../obj/members.obj.php')) {
    require_once '../../obj/members.obj.php';
}


if (file_exists('obj/members_roles.obj.php')) {
    require_once 'obj/members_roles.obj.php';
} elseif (file_exists('../obj/members_roles.obj.php')) {
    require_once '../obj/members_roles.obj.php';
} elseif (file_exists('../../obj/members_roles.obj.php')) {
    require_once '../../obj/members_roles.obj.php';

}

$connection = dbConnect();
$memberValidation = New Members_Roles();
$currentUser = "";

if (isset($_SESSION["username"])) {
    $currentUser = new Members($_SESSION["username"]);
    $currentUser->getAllDetails($connection);

    //Reset password checks - New security system (IE killing off md5 for good!)

    $url = $_SERVER["REQUEST_URI"];

    $pos = strrpos($url, "reset_pass.php");

    //If not on reset page then carry out checks
    if ($pos != true) {
        if ($currentUser->getReset() == 1) {
            header('Location: '.$domain.'members/reset_pass.php');
        }
    }

}


//Show edit link for each section if the url is on the correct viewing page
function showEditLink($domain, $view, $edit, $parm, $currentUser, $connection, $memberValidation)
{
    if ($_SERVER['PHP_SELF'] === $view) {
        $id = $_GET[$parm];
        $link = $domain . $edit . '?' . $parm . '=' . $id;
        echo '<li><a href="' . $link . '" role="link">Edit</a></li>';
    }
}

//Security Checks

//Gala
function galaFullAccess($connection, $currentUser, $memberValidation)
{
    if ($memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername()) || ($memberValidation->isMemberGalaCoordinator($connection, $currentUser->getUsername()))) {
        return true;
    } else {
        return false;
    }
}

function galaViewAccess($connection, $currentUser, $memberValidation)
{
    if (galaFullAccess($connection, $currentUser, $memberValidation)) {
        return true;
    } else if ($memberValidation->isMemberCommittee($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}

//Members
function memberFullAccess($connection, $currentUser, $memberValidation)
{
    if ($memberValidation->isMemberMembershipCoordinator($connection, $currentUser->getUsername()) || ($memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberCoach($connection, $currentUser->getUsername()))) {
        return true;
    } else {
        return false;
    }
}

function memberViewAccess($connection, $currentUser, $memberValidation)
{
    if (memberFullAccess($connection, $currentUser, $memberValidation)) {
        return true;
    } else if ($memberValidation->isMemberPresident($connection, $currentUser->getUsername()) || $memberValidation->isMemberSecretary($connection, $currentUser->getUsername()) || $memberValidation->isMemberTreasurer($connection, $currentUser->getUsername()) || $memberValidation->isMemberGalaCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberMembershipCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberBetaLeagueCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberHeadCoach($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}

//Squads
function squadFullAccess($connection, $currentUser, $memberValidation)
{

    if ($memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername()) || ($memberValidation->isMemberCommittee($connection, $currentUser->getUsername())) || $memberValidation->isMemberCoach($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}

function squadViewAccess($connection, $currentUser, $memberValidation)
{
    if (squadFullAccess($connection, $currentUser, $memberValidation)) {
        return true;
    } else if ($memberValidation->isMemberCoach($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}


//Venues
function venueFullAccess($connection, $currentUser, $memberValidation)
{
    if ($memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername()) || ($memberValidation->isMemberCommittee($connection, $currentUser->getUsername()))) {
        return true;
    } else {
        return false;
    }
}

function venueViewAccess($connection, $currentUser, $memberValidation)
{
    if (venueFullAccess($connection, $currentUser, $memberValidation)) {
        return true;
    } else if ($memberValidation->isMemberCoach($connection, $currentUser->getUsername()) || !$memberValidation->isMemberSwimmer($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}

//News
function newsFullAccess($connection, $currentUser, $memberValidation)
{
    if ($memberValidation->isMemberPresident($connection, $currentUser->getUsername()) || $memberValidation->isMemberSecretary($connection, $currentUser->getUsername()) || $memberValidation->isMemberTreasurer($connection, $currentUser->getUsername()) || $memberValidation->isMemberGalaCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberMembershipCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberBetaLeagueCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberHeadCoach($connection, $currentUser->getUsername()) || $memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}

//Gallery
//News
function galleryFullAccess($connection, $currentUser, $memberValidation)
{
    if ($memberValidation->isMemberPresident($connection, $currentUser->getUsername()) || $memberValidation->isMemberSecretary($connection, $currentUser->getUsername()) || $memberValidation->isMemberTreasurer($connection, $currentUser->getUsername()) || $memberValidation->isMemberGalaCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberMembershipCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberBetaLeagueCoordinator($connection, $currentUser->getUsername()) || $memberValidation->isMemberHeadCoach($connection, $currentUser->getUsername()) || $memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}




//Shop
function shopFullAccess($connection, $currentUser, $memberValidation)
{
    if ($memberValidation->isMemberSwimshop($connection, $currentUser->getUsername()) || $memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}


//TimeTable
function timetableFullAccess($connection, $currentUser, $memberValidation)
{
    if ($memberValidation->isMemberCommittee($connection, $currentUser->getUsername()) || $memberValidation->isMemberWebCoordinator($connection, $currentUser->getUsername())) {
        return true;
    } else {
        return false;
    }
}

?>