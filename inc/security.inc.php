<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 26/09/2016
 * Time: 17:22
 * Security file that is included on all pages to check if user has access to certain areas of the website.
**/

$domain='http://bucktest.dev/';

//Switch include paths when on root or sub folder
if(file_exists('obj/members.obj.php')) {
    require_once 'obj/members.obj.php';

} elseif(file_exists('../obj/members.obj.php')) {
    require_once '../obj/members.obj.php';
}
elseif(file_exists('../../obj/members.obj.php')) {
    require_once '../../obj/members.obj.php';
}



if(file_exists('obj/members_roles.obj.php')) {
    require_once 'obj/members_roles.obj.php';
} elseif(file_exists('../obj/members_roles.obj.php')) {
    require_once '../obj/members_roles.obj.php';

}
elseif(file_exists('../../obj/members_roles.obj.php')) {
    require_once '../../obj/members_roles.obj.php';

}


$connection = dbConnect();
$memberValidation = New Members_Roles();

if(isset($_SESSION["username"]))
{
    $currentUser = new Members($_SESSION["username"]);
    //$currentUser->getUsername();
    if ($memberValidation->isMemberCommittee($connection,$currentUser->getUsername()))
    {
       // echo "Committee Member ";
    }
    else{
        //echo "Other people";
    }
}



//$memberValidation->isMemberCommittee($connnection,$currentUser);

//Show edit link for each section if the url is on the correct viewing page
function showEditLink($domain,$view, $edit, $parm, $currentUser, $connection, $memberValidation)
{
    if($_SERVER['PHP_SELF'] === $view) {
        $id = $_GET[$parm];
        $link = $domain . $edit. '?' . $parm . '=' . $id;
        if ($memberValidation->isMemberCommittee($connection,$currentUser)){

            echo '<li><a href="'.$link.'" role="link">Edit</a></li>';
        }
    }
}

?>