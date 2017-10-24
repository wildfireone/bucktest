<?php
    session_start();


    require '../inc/connection.inc.php';
    require '../inc/security.inc.php';

    if (is_null($_GET["id"]) || !is_numeric($_GET["id"])) {        
            header( 'Location:' . $domain . '404.php' );
            die();
    }

    if (!isset($_SESSION['username'])) {
        header( 'Location:' . $domain . 'message.php?id=badaccess' );
        exit;
    }

    if(!shopFullAccess($connection, $currentUser, $memberValidation)) {
            header( 'Location:' . $domain . 'message.php?id=badaccess' );
            exit;
    }
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>View | Shop | Bucksburn Amateur Swimming Club</title>  
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
                <li><a href="../shop.php" role="link">Shop</a></li>
                <li class="current">View a Shop Item</li>
            </ul>

            <h2>View a Shop Item</h2>
            
            <?php
                require '../obj/shop.obj.php';
                require '../inc/forms.inc.php';

                $conn = dbConnect();

                $shopItem = new Shop($_GET["id"]);
                $shopItem->getAllDetails($conn);


                if (isset($_SESSION['create'])) {
                    echo '<p class="alert-box success radius centre">Item added successfully!</p>';
                    unset($_SESSION['create']);
                }
                if (isset($_SESSION['update'])) {
                    echo '<p class="alert-box success radius centre">Changes saved successfully!</p>';
                    unset($_SESSION['update']);
                }

                echo formStart();
                echo '<div class="large-9 large-centered medium-12 small-12 columns"><fieldset><legend>Shop Item Details</legend>';

                echo textInputSetup(false, "Item ID", "txtID", $shopItem->getID(), 3, true);
                echo textInputSetup(false, "Item Name", "txtName", $shopItem->getName(), 50, true);
                echo textareaInputSetup(false, "Item Description", "txtDescription", $shopItem->getDescription(), 250,3, true);
                echo moneyInputSetup(false, "Item Price", "txtPrice", $shopItem->getPrice(), 6, true);
                echo textInputSetup(false, "Item Quantity", "txtQuantity", $shopItem->getQuantity(), 3, true);

                echo linkButton("Edit this Shop Item", 'edit.php?id=' . $shopItem->getID());

                echo '</fieldset></div>';
                echo formEnd();

                dbClose($conn);
            ?>   
        </div>
    </div>    
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
