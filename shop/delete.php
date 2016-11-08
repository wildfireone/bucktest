<?php
    session_start();

    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';
    require '../obj/shop.obj.php';

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!shopFullAccess($connection, $currentUser, $memberValidation)) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }
    
    if (isset($_POST['btnSubmit'])) {
        
        $connection = dbConnect();
        $shop = new Shop($_GET['id']);
            
        if ($shop->delete($connection)) {
            $_SESSION['delete'] = true;

            header('Location:' .$domain . 'shop.php');
            die();
        } else {
            $_SESSION['error'] = true;
        }
        dbClose($connection);
    } 
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Delete | Shop | Bucksburn Amateur Swimming Club</title>
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
                <li><a href="../shop.php" role="link">Shop</a></li>
                <li class="current">Delete a Shop Item</li>
            </ul>
    
            <h2>Delete Shop Item</h2>
            
            <?php
                require '../inc/forms.inc.php';

                $conn = dbConnect();

                $shopItem = new Shop($_GET["id"]);
                $shopItem->getAllDetails($conn);

                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error deleting the item from the shop. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                echo formStart(false);

                echo '<div class="panel"><p class="centre middle">Are you sure you want to delete the shop item <b>' . $shopItem->getName() . '</b>?</p></div>';

                echo formEndWithDeleteButton("Delete");

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
