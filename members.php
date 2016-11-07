<?php
    session_start();
    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';
    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!memberViewAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en-GB">

    <head>    
        <?php include 'inc/meta.inc.php'; ?>
        <title>Members | Bucksburn Amatuer Swimming Club</title>   
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
                    <li class="current">Members</li>
                </ul>

                <h2>Members</h2>   

                <?php

                require_once 'obj/members.obj.php';
                require 'obj/status.obj.php';
                require 'obj/squads.obj.php';
                require 'obj/roles.obj.php';
                require 'inc/forms.inc.php';
                require_once 'obj/members_roles.obj.php';

                if (isset($_SESSION['delete'])) {
                    echo '<p class="alert-box success radius centre">Member deleted successfully!</p>';
                    unset($_SESSION['delete']);
                }

                $conn = dbConnect();

                $status = new Status();
                $squads = new Squads();
                $roles = new Roles();
                $members_roles = New Members_Roles();

                echo '<ul class="accordion" data-accordion>
                  <li class="accordion-navigation">
                   <input type="button" name="answer" value="Search Members" onclick="toggle_visibility(\'search\')" />
                      <div id="search" class="content">';


                echo formStart();
                if (isset($_POST["btnSubmit"])) {
                    if (!empty($_POST["sltStatus"])) {
                        echo comboInputPostback(false, "Search by Member Status", "sltStatus", $_POST["sltStatus"], $status->listAllStatus($conn));
                    } else {
                        echo comboInputBlank(false, "Search by Member Status", "sltStatus", "All", $status->listAllStatus($conn));
                    }

                    if (!empty($_POST["sltSquad"])) {
                        echo comboInputPostback(false, "Search by Squad", "sltSquad", $_POST["sltSquad"], $squads->listAllSquads($conn));
                    } else {
                        echo comboInputBlank(false, "Search by Squad", "sltSquad", "All", $squads->listAllSquads($conn));
                    }

                    if (!empty($_POST["sltRoles"])) {
                        echo comboInputPostback(false, "Search by Member Role", "sltRole", $_POST["sltRole"], $roles->listAllRoles($conn));
                    } else {
                        echo comboInputBlank(false, "Search by Member Role", "sltRole", "All", $roles->listAllRoles($conn));
                    }

                    if (!empty($_POST["txtSearch"])) {
                        echo textInputPostback(false, "Search by Member Name", "txtSearch", $_POST["txtSearch"], 100);
                    } else {
                        echo textInputBlank(false, "Search by Member Name", "txtSearch", 100);
                    }
                } else {
                    echo comboInputBlank(false, "Search by Member Status", "sltStatus", "All", $status->listAllStatus($conn));
                    echo comboInputBlank(false, "Search by Squad", "sltSquad", "All", $squads->listAllSquads($conn));
                    echo comboInputBlank(false, "Search by Member Role", "sltRole", "All", $roles->listAllRoles($conn));
                    echo textInputBlank(false, "Search by Member Name", "txtSearch", 100);
                }

                echo formEndWithButton("Search");

                echo '</div></li></ul>';


                $memberItem = new Members();

                if (isset($_POST["btnSubmit"])) {

                    //Variables
                    $status = null;
                    $squad = null;
                    $role = null;
                    $name = null;

                    //Set form data to variables if they are present:
                    if (!empty($_POST["sltStatus"])) {
                        $status = $_POST["sltStatus"];
                    }

                    if (!empty($_POST["sltSquad"])) {
                        $squad = $_POST["sltSquad"];
                    }

                    if (!empty($_POST["sltRole"])) {
                        $role = $_POST["sltRole"];
                    }

                    if (!empty($_POST["txtSearch"])) {
                        $name = $_POST["txtSearch"];
                    }

                    $membersList = $memberItem->listMemberSearchResults($conn, $status, $squad, $role, $name);
                    //var_dump($membersList);

                    /* //Search by members name:
                     if (!empty($_POST["txtSearch"])) {
                         $membersList = $memberItem->listAllMembers($conn, $_POST["txtSearch"]);
                     }


                     if (!empty($_POST["sltSquad"])) {
                         $squadID = $_POST['sltSquad'];
                         $membersList = $memberItem->listAllSquadMembers($conn,$squadID);
                     }*/

                }

                else {
                    if(isset($_GET['squadID']))
                    {
                        $squadID = $_GET['squadID'];
                        $membersList = $memberItem->listAllSquadMembers($conn,$squadID);
                    }
                    else{
                        $membersList = $memberItem->listAllCurrentMembers($conn);
                    }
                }

                echo '<p class="right"><b>' . count($membersList) . ' results</b></p>';

                echo '<table class="large-12 medium-12 small-12 columns">
                        <tr role="row">
                            <th role="columnheader">Name</th>
                            <th role="columnheader">Squad</th>
                            <th role="columnheader">Role</th>
                            <th role="columnheader">Details</th>
                            <th role="columnheader">Peronal Bests</th>
                            <th role="columnheader">Swim Times</th>
                            <th></th>
                        </tr>';

                foreach ($membersList as $member) {
                    $memberItem->setUsername($member["username"]);
                    $memberItem->getAllDetails($conn);

                    //set hyperlink to point to member search with squadID parameter
                    $link = "members/view.php?u=" . $memberItem->getUsername();

                    echo "<tr>";
                    echo '<td data-th="Name">' . $memberItem->getFirstName() . ' ' . $memberItem->getLastName() . '</td>';
                    if (!empty($member["squad"])) {
                        echo '<td data-th="Squad">' . $member["squad"] . '</td>';
                    } else {
                        echo '<td data-th="Squad"></td>';
                    }

                    echo '<td data-th="Role">';
                    echo listMemberRoles($members_roles->getAllRolesForMember($conn, $memberItem->getUsername()), $roles->listAllRoles($conn));
                    echo '</td>';
                    echo '<td class="none"><a href="members/view.php?u=' . $memberItem->getUsername() . '">View Details</td>';
                    echo '<td class="none"><a href="my-pbs.php?u=' . $memberItem->getUsername() . '">View PBs</td>';
                    echo '<td class="none"><a href="my-results.php?u=' . $memberItem->getUsername() . '">Swim Times</td>';
                    echo "</tr>";
                }
                dbClose($conn);
                ?>
                </table>
            </div>
        </div>
        <?php include 'inc/footer.inc.php';?>
    </body>

</html>
