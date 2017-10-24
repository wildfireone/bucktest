<?php
    session_start();
    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Join Us | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
</head>

<body>
    <?php include '../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
        <ul class="breadcrumbs">
            <li><a href="../index.php" role="link">Home</a></li>
            <li><a href="../pages/view.php?id=1" role="link">About</a></li>
            <li class="current">Join Us</li>
        </ul>
        
        <h2>Join Us</h2>
        
        <p>Some blurb text about joining the club with a link to <a href="../contact.php">get in touch</a> for more information.</p>
        
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
