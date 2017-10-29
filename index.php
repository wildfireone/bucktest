<?php
session_start();
require 'inc/connection.inc.php';
require 'inc/security.inc.php';

$conn = dbConnect();
$aboutPage = new Pages();
$aboutPage->setPageTitle('Front page Description');

//Edit Shop Description
if (isset($_POST['btnEditShopDescription'])) {
    $_SESSION['editDescription'] = 'index.php';
    $aboutPage->getAllDetailsByTitle($conn);
    header('Location:' . $domain . 'pages/edit.php?id=' .  $aboutPage->getPageID());
    die();
}

?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include 'inc/meta.inc.php'; ?>
    <title>Home | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script
</head>

<body>
<?php include 'inc/header.inc.php'; ?>
<br>
<div class="row" id="content">
    <div class="large-12 columns centre">
        <?php

        require 'obj/news.obj.php';
        require 'obj/galas.obj.php';
        require 'obj/venues.obj.php';
        require 'obj/gallery_albums.obj.php';
        require 'obj/gallery_photos.obj.php';


        $conn = dbConnect();
        $newsItem = new News();
        $newsList = $newsItem->getMostRecent($conn, 3);
        $gala = new Galas();
        $galaList = $gala->listAllCompletedGalas($conn, 2);
        $venue = new Venues();

        $shopPage = new Pages();
        $shopPage->setPageTitle('Shop Description');

        //         PHOTO GALLERY CODE - START
        $albums = new gallery_album("3");
        if ($albums->doesExist($conn)) {
            $photos = new gallery_photos();
            $photos->setAlbumID($albums->getAlbumID());
            $photoList = $photos->listPhotoAlbum($conn);
        } else {
            $photos = null;
            $photoList = null;
        }

        echo '<div class="sc">';
        if ($photoList != null) {

            $count = 0;
            $max = sizeof($photoList);

            foreach ($photoList as $photos) {
                $photo = new gallery_photos();
                $photo->setPhotoID($photos["photoID"]);
                $photo->getAllDetails($conn);
                $count += $count;
                echo '          <div>
                                <img width="500px" height="341px" src="' . $photo->getFullFilePath() . '"/>
                                </div>
                            ';
            }
            echo "</div>";
        } else {
            echo '
                    <div>
                        <img src="img/slider/slider_1.png"/>
                    </div>
                    <div>
                        <img src="img/slider/slider_3.png"/>
                    </div>
                     <div>
                        <img src="img/slider/slider_4.png"/>
                    </div>
               </div>
        
                      ';
        }
        //         PHOTO GALLERY CODE END

        //         About US CODE START
        if($aboutPage->getAllDetailsByTitle($conn))
        {
            echo '<div class="row">
                <div class="large-6 medium-6 small-12 columns index">
                    <h2 class="h3 capitalise">About Us</h2>
                    <p>'.$aboutPage->getPageContent().'</p> <div class="large-6 large-centered middle-12 small-12 columns">
                    <a href="/pages/view.php?id=1" class="button small radius h6 capitalise margin">Read more About Us</a>
                    </div><br/>';
            if (isset($_SESSION["username"]) && (pagesFullAccess($connection, $currentUser, $memberValidation))) {
                echo '<form action="" method="post"><input name="btnEditShopDescription" value=" Edit Front Page Description" class="button"
                                                        type="submit"></h3></form>';
                //echo linkButton2("Edit Shop Page Description", '/pages/edit.php?id=' . $shopPage->getPageID());

            }
            echo '</div>';
        } else{ //Otherwise show placeholder text
            echo '<div class="row">
                <div class="large-6 medium-6 small-12 columns index">
                    <h2 class="h3 capitalise">About Us</h2>
                    <p>Bucksburn Amateur Swimming Club (BASC) is a fairly young club with over 100 swimmers, which hosts a wide range of age groups and abilities. BASC was formed in 1990, and at that time had only 1 core aim - to encourage local participation in the sport of swimming.</p>
                    <div class="large-6 large-centered middle-12 small-12 columns">
                    <a href="/pages/view.php?id=1" class="button small radius h6 capitalise margin">Read more About Us</a>
                    </div>
                    </div>';
        }
        //         About US CODE END
        echo '<div class="large-6 medium-6 small-12 columns index">
                    <h2 class="h3 capitalise">Recent Gala Results</h2>
                    <div class="row">';

        foreach ($galaList as $galaItem) {
            $gala->setID($galaItem["id"]);
            $gala->getAllDetails($conn);
            $venue->setID($galaItem["venueID"]);
            $venue->getAllDetails($conn);
            $link = "gala-results/view.php?id=" . $gala->getID();

            echo '<div class="large-12 medium-12 small-12 columns centre">
                            <h3 class="h4 capitalise"><a href="' . $link . '">' . $gala->getTitle() . '</a></h3>
                            <h4 class="h6"><i>' . $venue->getVenue() . ' - ' . date("d/m/Y", strtotime($gala->getDate())) . '</i></h4>
                    </div>';
        }
        echo '</div><div class="large-6 large-centered middle-12 small-12 columns">
                    <a href="gala-results.php" class="button small radius h6 capitalise margin">View all Gala Results</a>
                    </div></div></div><hr>';

        echo '<div class="large-12 medium-12 small-12 columns index">
                    <h2 class="h3 capitalise">Recent News</h2>
                    <div class="row">';

        foreach ($newsList as $news) {
            $newsItem->setID($news["id"]);
            $newsItem->getAllDetails($conn);
            $link = "news/view.php?id=" . $newsItem->getID();

            echo '<div class="large-4 medium-4 small-12 columns centre">
                        <article>
                            <h3 class="h4 capitalise"><a href="' . $link . '">' . $newsItem->getTitle() . '</a></h3>
                            <h4 class="h6"><i>' . date("l jS F Y", strtotime($newsItem->getDate())) . '</i></h4>
                            <p>' . $newsItem->getSummary(100) . '</p>
                        </article>
                    </div>';
        }
        echo '</div>
                <div class="large-3 large-centered middle-6 small-12 columns">
                    <a href="news.php" class="button small radius h6 capitalise">View all Recent News</a>
                </div>
                </div>';
        ?>

    </div>
</div>
<footer class="footer clearfix">
    <div class="large-12 medium-12 small-12 columns">
        <div class="row">
            <br>
            <span class="copyright centre middle">© <?php echo date("Y"); ?> Bucksburn Amateur Swimming Club</span>
        </div>
    </div>
</footer>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script
<script src="https://bucksburnasc.org.uk/css/slick/slick.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://bucksburnasc.org.uk/css/slick/slick.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.sc').slick({
            dots: true,
            speed: 150,
            centerMode: true,
            autoplay: true,
            infinite: true,
        });
    });
</script>
</body>
</html>
