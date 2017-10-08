<?php session_start();
    unset($_SESSION);
    session_destroy();
    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Home | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>     
    <link href="css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include 'inc/header.inc.php'; ?>   
    <br>
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="index.php" role="link">Home</a></li>
                <li class="current">Logout</li>
            </ul>

            <h2>Logout</h2>

            <p>You have been successfully logged out of the Bucksburn Amateur Swimming Club website.</p>

            <p>You can now <a href="index.php">return to the homepage</a> or <a href="login.php">login again.</a></p>        
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
