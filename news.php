<?php
session_start();
require 'inc/connection.inc.php';
require 'inc/security.inc.php';
require 'inc/pagination.inc.php';

/*
* Set the current page number
*/
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$conn = dbConnect();


/*
 * Set the options
 */
$options = array(
    'results_per_page' => 25,
    'url' => $domain . 'news.php?page=*VAR*',
    'db_handle' => $conn
);


/*
 * Call the class, the var's are:
 *
 * pagination(int $surrent_page, string $query, array $options)
 */
try {
    $pagination = new pagination($page, 'SELECT id FROM news ORDER BY date DESC', $options);
} catch (paginationException $e) {
    echo $e;
    exit();
}


?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include 'inc/meta.inc.php'; ?>
    <title>News | Bucksburn Amateur Swimming Club</title>
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>
    <link href="css/site.css" rel="stylesheet"/>
</head>

<body>
<?php include 'inc/header.inc.php'; ?>
<br>

<div class="row" id="content">
    <div class="large-12 medium-12 small-12 columns">

        <ul class="breadcrumbs">
            <li><a href="index.php" role="link">Home</a></li>
            <li class="current">News</li>
        </ul>


        <section role="main">
            <h2>News</h2>

            <?php


            require 'obj/news.obj.php';
            require_once 'obj/members.obj.php';

            if (isset($_SESSION['delete'])) {
                echo '<p class="alert-box success radius centre">News Item deleted successfully!</p>';
                unset($_SESSION['delete']);
            }

            if ($pagination == true) {
                echo ' <div class="breadcrumbs">';
                echo $pagination->links_html;
                echo '</div>';

            }

            $conn = dbConnect();

            $newsItem = new News();
            $memberItem = new Members();


            /*
             * If all was successful, we can do something with our results
             */
            if ($pagination->success == true) {
                /*
                 * Get the results
                 */
                $newsList = $pagination->resultset->fetchAll();


                foreach ($newsList as $news) {
                    $newsItem->setID($news["id"]);
                    $newsItem->getAllDetails($conn);
                    $memberItem->setUsername($newsItem->getAuthor());

                    $link = "news/view.php?id=" . $newsItem->getID();

                    echo '<article>
                    <h3><a href="' . $link . '">' . $newsItem->getTitle() . '</a></h3>
                    <h4 class="capitalise">' . $newsItem->getSubtitle() . '</h4>
                    <h5><i>By ' . $memberItem->getFullNameByUsername($conn) . ' on ' . date("jS F Y", strtotime($newsItem->getDate())) . '</i></h5>
                    <p>' . $newsItem->getSummary(250) . '</p>
                    </article><hr>';
                }


                /*
                 * Show the paginationd links ( 1 2 3 4 5 6 7 ) etc.
                 */
                echo ' <div class="breadcrumbs">';
                echo $pagination->links_html;
                echo '</div>';


                /*
                 * Get the total number of results if you like
                 */
                echo "<h2>Statistics</h2>";
                echo "The total number of news articles that been posted since <i>" . $newsItem->getOldestDate($conn) . "</i> is : <b>" . $pagination->total_results . " </b>";
            }


            dbClose($conn);
            ?>
        </section>

    </div>
</div>
<?php include 'inc/footer.inc.php'; ?>
</body>

</html>
