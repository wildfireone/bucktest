<?php 
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/galas.obj.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!galaFullAccess($connection,$currentUser,$memberValidation))
    {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }




    if (is_null($_GET["id"])) {        
        header( 'Location:' . $domain . '404.php' );
        exit;
    } else {
        $galas = new Galas();
        if (!$galas->doesExist($connection,$_GET["id"])) {
            header( 'Location:' . $domain . '404.php' );
            exit;
        }
    }

    $galas->setID($_GET["id"]);
    if (isset($_POST['btnSubmit'])) {
        if ($galas->delete($connection)) {
            $_SESSION['delete'] = true;

            header('Location:' .$domain . 'galas.php');
            die();
        } else {
            $_SESSION['error'] = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Delete | Gala | Bucksburn Amatuer Swimming Club</title>  
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>     
    <link href="../css/site.css" rel="stylesheet"/>
</head>

<body>   
    <?php include '../inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="../index.php" role="link">Home</a></li>
                <li><a href="../galas.php" role="link">Galas</a></li>
                <li class="current">Delete a Gala</li>
            </ul>
            
            <h2>Delete a Gala</h2>
            
            <?php
                require '../inc/forms.inc.php';            

                $conn = dbConnect();

                $gala = new Galas($_GET["id"]);
                $gala->getAllDetails($conn);
                
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the Gala. Please try again.</p>';
                    unset($_SESSION['error']);
                }
    
                echo formStart(false);
                echo '<div class="panel"><p class="centre">Are you sure you want to delete the gala <b>"' . $gala->getTitle() . '"</b>? This will also delete all associated events and swimming times of this gala.<br/><br/><b>This action cannot be undone.</b></p></div>';
                echo formEndWithDeleteButton("Delete");
                dbClose($conn);
                ?>
                
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
