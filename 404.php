<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?> 
    <title>Page Not Found | Bucksburn Amateur Swimming Club</title>      
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>   
    <link href="css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include 'inc/header.inc.php';?> 
    <br>
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
            
            <ul class="breadcrumbs">
                <li><a href="index.php" role="link">Home</a></li>
                <li class="current">Page Not Found</li>
            </ul>

            <h2>Page Not Found</h2>
        
            <div class="large-6 medium-12 small-12 columns">
                <img src="img/404.jpg" alt="" />
            </div>
        
            <div class="large-6 medium-12 small-12 columns">
                <h2>Sorry, but the page you are looking for could not be found.</h2>

                <h3>You could try:</h3>
                <ul>
                    <li>Checking the url is correct and try again</li>
                    <li>Accessing the page you are looking for from the <a href="index.php">homepage</a></li>
                    <li><a href="contact.php">Contacting us</a> if you believe an error has occurred</li>
                </ul>
            </div>
        </div>
     </div>
     <?php include 'inc/footer.inc.php';?>
</body>

</html>