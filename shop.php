<?php
    session_start();
    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';
    include 'inc/forms.inc.php';

//Get Shop Description if exists
$conn = dbConnect();


$shopPage = new Pages();
$shopPage->setPageTitle('Shop Description');

//Edit Shop Description
if (isset($_POST['btnEditShopDescription'])) {
    $_SESSION['editDescription'] = 'shop.php';
    $shopPage->getAllDetailsByTitle($conn);
    header('Location:' . $domain . 'pages/edit.php?id=' .  $shopPage->getPageID());
    die();
}

?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Shop | Bucksburn Amateur Swimming Club</title>    
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>   
    <link href="css/site.min.css" rel="stylesheet"/>
</head>

<body>   
    <?php include 'inc/header.inc.php';?>   
    <br>
        
    <div class="row" id="content">
        <div class="row-12 columns">
        
            <ul class="breadcrumbs">
                <li><a href="index.php" role="link">Home</a></li>
                <li class="current">Shop</li>
            </ul>
        
            <h2>Shop</h2>   

            <?php
                if (isset($_SESSION['delete'])) {
                    echo '<p class="alert-box success radius centre">Shop Item deleted successfully!</p>';
                    unset($_SESSION['delete']);
                }



                if($shopPage->getAllDetailsByTitle($conn))
                {
                    echo'<p>'.$shopPage->getPageContent().'</p>';

                    if (isset($_SESSION["username"]) && (pagesFullAccess($connection, $currentUser, $memberValidation))) {
                        echo '<form action="" method="post"><input name="btnEditShopDescription" value="Edit Shop Page Description" class="button"
                                                        type="submit"></h3></form>';
                        //echo linkButton2("Edit Shop Page Description", '/pages/edit.php?id=' . $shopPage->getPageID());

                        echo '<br/>';
                    }

                } else{ //Otherwise show placeholder text
                    echo '<p>This is a space for some explanatory text about the items contained in the BASC shop. </p>';
                }

            ?> 



            <table class="large-12 medium-12 small-12 columns">      
                <tr>
                    <th>Item Name</th>
                    <th>Item Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <!--<th></th>-->
                </tr>
            
            <?php
                require 'obj/shop.obj.php';
                


                $shopItem = new Shop();
                $shopList = $shopItem->listAllShopItems($conn);

                if (count($shopList) == 0) {
                    echo '<tr><td class="centre" colspan="5">No shop items were found.</td></tr>';
                        
                } else {
                    foreach ($shopList as $item) {
                        $shopItem->setID($item["id"]);
                        $shopItem->getAllDetails($conn);            

                        $shopViewLink = "shop/view.php?id=" . $shopItem->getID();

                        echo '<tr>';
                        echo '<td data-th="Name">' . $shopItem->getName() . "</td>";
                        echo '<td data-th="Description">' . $shopItem->getDescription() . "</td>";
                        echo '<td data-th="Price">£' . $shopItem->getPrice() . '</td>';
                        echo '<td data-th="Quantity">' . $shopItem->getQuantity() . '</td>';
                        if (isset($_SESSION["username"]) && (shopFullAccess($connection, $currentUser, $memberValidation)))  {
                            echo '<td class="none"><a href="' . $shopViewLink . '">View Details</a></td>';
                        }
                        echo '</tr>';
                    }
                }
                dbClose($conn);
            ?>
                
            </table>
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
