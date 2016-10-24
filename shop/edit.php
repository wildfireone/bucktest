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
        
        if ($shop->isInputValid($_POST['txtName'], $_POST['txtDescription'], $_POST['txtPrice'], $_POST['txtQuantity'])) {
            $shop->setName($_POST['txtName']);
            $shop->setDescription($_POST['txtDescription']);
            $shop->setPrice($_POST['txtPrice']);
            $shop->setQuantity($_POST['txtQuantity']);
            
            if ($shop->update($connection)) {
                $_SESSION['update'] = true;

                header('Location:' .$domain . '/shop/view.php?id=' . $shop->getID());
                die();
            } else {
                $_SESSION['error'] = true;
            }
        } else {
            $_SESSION['invalid'] = true;
        }
        dbClose($connection);
    } 
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include '../inc/meta.inc.php';?>
    <title>Edit | Shop | Bucksburn Amatuer Swimming Club</title>  
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
                <li class="current">Edit a Shop Item</li>
            </ul>
    
            <h2>Edit Shop Item</h2>
            
            <?php
                require '../inc/forms.inc.php';

                $conn = dbConnect();

                $shopItem = new Shop($_GET["id"]);
                $shopItem->getAllDetails($conn);

                if (isset($_SESSION['invalid'])) {
                    echo '<p class="alert-box error radius centre">Some of the input you provided was invalid. Please correct the highlighted errors and try again.</p>';
                    unset($_SESSION['invalid']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<p class="alert-box error radius centre">There was an error adding the item to the shop. Please try again.</p>';
                    unset($_SESSION['error']);
                }

                echo formStart();
                echo '<div class="large-9 large-centered medium-12 small-12 columns"><fieldset><legend>Shop Item Details</legend>';

                echo textInputSetup(true, "Item ID", "lblID", $shopItem->getID(), 3, true);

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtName"])) {
                        echo textInputEmptyError(true, "Item Name", "txtName", "errEmptyName", "Please enter a Item Name",50);
                    } else {
                        echo textInputPostback(true, "Item Name", "txtName", $_POST["txtName"], 50);
                    }
                } else {
                    echo textInputSetup(true, "Item Name", "txtName", $shopItem->getName(), 50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textareaInputPostback(false, "Item Description", "txtDescription", $_POST["txtDescription"], 250,3);
                } else {
                    echo textareaInputSetup(false, "Item Description", "txtDescription", $shopItem->getDescription(), 250,3);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtPrice"])) {
                        echo moneyInputEmptyError(true, "Item Price", "txtPrice", "errEmptyPrice", "Please enter a Item Price that is more than zero", 6);
                    } else {
                        if ($shopItem->isPriceValid($_POST["txtPrice"])) {
                            echo moneyInputPostback(true, "Item Price", "txtPrice", $_POST["txtPrice"], 6);
                        } else {
                            echo moneyInputPostbackError(true, "Item Price", "txtPrice", $_POST["txtPrice"], "errInvalidPrice", "Please enter an Item Price that is a valid number and less than £1,000", 6);
                        }
                    }
                } else {
                    echo moneyInputSetup(true, "Item Price", "txtPrice", $shopItem->getPrice(), 6);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtQuantity"])) {
                        echo textInputEmptyError(true, "Item Quantity", "txtQuantity", "errEmptyQuantity", "Please enter a Item Quantity that is more than zero", 3);
                    } else {
                        if ($shopItem->isQuantityValid($_POST["txtQuantity"])) {
                            echo textInputPostback(true, "Item Quantity", "txtQuantity", $_POST["txtQuantity"], 3);
                        } else {
                            echo textInputPostbackError(true, "Item Quantity", "txtQuantity", $_POST["txtQuantity"], "errInvalidQuantity", "Please enter an Item Quantity that is a valid number", 3);
                        }
                    }
                } else {
                    echo textInputSetup(true, "Item Quantity", "txtQuantity", $shopItem->getQuantity(), 3);
                }

                echo '</fieldset></div>';
                echo formEndWithButton("Save changes","delete.php?id=".$shopItem->getID());

                dbClose($conn);
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
