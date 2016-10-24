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
        $shop = new Shop();
        
        if ($shop->isInputValid($_POST['txtName'], $_POST['txtDescription'], $_POST['txtPrice'], $_POST['txtQuantity'])) {
            $shop->setName($_POST['txtName']);
            $shop->setDescription($_POST['txtDescription']);
            $shop->setPrice($_POST['txtPrice']);
            $shop->setQuantity($_POST['txtQuantity']);
            
            if ($shop->create($connection)) {
                $_SESSION['create'] = true;

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
    <title>Create | Shop | Bucksburn Amatuer Swimming Club</title>
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
                <li class="current">Create a Shop Item</li>
            </ul>
    
            <h2>Create a Shop Item</h2>
            
            <?php
                require '../inc/forms.inc.php';

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

                $shopValidation = new Shop();

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtName"])) {
                        echo textInputEmptyError(true, "Item Name", "txtName", "errEmptyName", "Please enter an Item Name", 50);
                    } else {
                        echo textInputPostback(true, "Item Name", "txtName", $_POST["txtName"], 50);
                    }
                } else {
                    echo textInputBlank(true, "Item Name", "txtName", 50);
                }

                if (isset($_POST["btnSubmit"])) {
                    echo textareaInputPostback(false, "Item Description", "txtDescription", $_POST["txtDescription"], 250,3);
                } else {
                    echo textareaInputBlank(false, "Item Description", "txtDescription", 250,3);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtPrice"])) {
                        echo moneyInputEmptyError(true, "Item Price", "txtPrice", "errEmptyPrice", "Please enter an Item Price that is more than zero", 6);
                    } else {
                        if ($shopValidation->isPriceValid($_POST["txtPrice"])) {
                            echo moneyInputPostback(true, "Item Price", "txtPrice", $_POST["txtPrice"], 6);
                        } else {
                            echo moneyInputPostbackError(true, "Item Price", "txtPrice", $_POST["txtPrice"], "errInvalidPrice", "Please enter an Item Price that is a valid number and less than £1,000", 6);
                        }
                    }
                } else {
                    echo moneyInputBlank(true, "Item Price", "txtPrice", 6);
                }

                if (isset($_POST["btnSubmit"])) {
                    if (empty($_POST["txtQuantity"])) {
                        echo textInputEmptyError(true, "Item Quantity", "txtQuantity", "errEmptyQuantity", "Please enter an Item Quantity that is more than zero", 3);
                    } else {
                        if ($shopValidation->isQuantityValid($_POST["txtQuantity"])) {
                            echo textInputPostback(true, "Item Quantity", "txtQuantity", $_POST["txtQuantity"], 3);
                        } else {
                            echo textInputPostbackError(true, "Item Quantity", "txtQuantity", $_POST["txtQuantity"], "errInvalidQuantity", "Please enter an Item Quantity that is a valid number", 3);
                        }
                    }
                } else {
                    echo textInputBlank(true, "Item Quantity", "txtQuantity", 3);
                }

                echo '</fieldset></div>';
                echo formEndWithButton("Add Item to Shop");
            ?>
            
        </div>
    </div>
    <?php include '../inc/footer.inc.php';?>
</body>

</html>
