<?php
    session_start();
    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';


//
function myobfiscate($emailaddress)
{
    $email = $emailaddress;
    $obfuscatedEmail = null;
    $length = strlen($email);
    for ($i = 0; $i < $length; $i++) {
        $obfuscatedEmail .= "&#" . ord($email[$i]) . ";";
    }
    return $obfuscatedEmail;
}

?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Committee | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#revealEmails").click(function () {
                $(".emailHider").toggle();
            });
        });
    </script>
</head>

<body>   
    <?php include '../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
        <ul class="breadcrumbs">
            <li><a href="../index.php" role="link">Home</a></li>
            <li><a href="../pages/view.php?id=1" role="link">About</a></li>
            <li class="current">Committee</li>
        </ul>
    
        <h2>Committee</h2>

            <p>The committee for the current session: <br/>
            <div class="small-6 small-centered text-center columns">
                <input id="revealEmails" class="button text-center" type="button" value="Reveal Emails"/>
            </div>
            </p>


            <?php
            require_once '../obj/members_roles.obj.php';
            require '../obj/roles.obj.php';
            require_once '../obj/members.obj.php';

            $conn = dbConnect();
            
            $members_roles = new Members_Roles();
            $role = new Roles();
            $member = new Members();
            
            $committee = $members_roles->listCommittee($conn);

            foreach ($committee as $item =>$value) {
                $role->setID($item);
                $role->getAllDetails($conn);

                echo '<div class="large-4 medium-6 small-12 columns centre">';
                echo '<h3>' . $role->getRole() . '</h3>';
                $list = '';
                foreach ($value as $thing) {
                    $member->setUsername($thing);
                    $member->getAllDetails($conn);
                    $list .= $member->getFirstName() . ' ' . $member->getLastName() . ', ';
                }
                echo '<span class="h4">' . substr($list, 0, (count($list) - 3)) . '</span>';
                if (!empty($role->getEmail())) {
                    echo '<div class="emailHider"><a href="mailto:' . myobfiscate($role->getEmail()) . '"/>' . myobfiscate($role->getEmail()) . '</a></div>';
                }                
                echo '</div>';
            }
        ?>
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
