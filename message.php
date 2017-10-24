<?php
    session_start();
    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Members' Area | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>    
    <link href="css/site.min.css" rel="stylesheet"/>
</head>

<body>   
    <?php include 'inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
        <ul class="breadcrumbs">
            <li><a href="index.php" role="link">Home</a></li>
            <li class="current">Message</li>
        </ul> 
        
        <?php 
            if($_GET['id'] == 'badaccess') {
                echo '<h1>Access Denied</h1>
                <p>You do not have the required permissions to access that area.</p>
                <p>You could try:</p>
                <ul>';

                if (!isset($_SESSION['username'])) {
                    echo '<li><a href="login.php">Logging in</a> to the website</li>';
                }
                    
                echo '<li>Returning to the <a href="index.php">homepage</a></li>
                </ul>';
            }
        ?>
        
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
