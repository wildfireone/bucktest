<?php
    session_start();
    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';
    if (!isset($_SESSION['username'])) {
        header('location: ' . $domain . '/message.php?id=badaccess');
        die();
    }

    $currentUser->setLastLoginDate( $_SESSION['lastLogin']);
?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
    <?php include 'inc/meta.inc.php';?>
    <title>Members' Area | Bucksburn Amateur Swimming Club</title>
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
                <li class="current">Member's Area</li>
            </ul>

            <div class="large-8 medium-6 small-12 columns">
                <h2 class="inline">Members' Area</h2>
            </div>

            <div class="large-4 medium-6 small-12 columns centre">
                <?php echo '<a href="my-details.php" class="small radius button capitalise h6">My Details</a>'; ?>
                <?php
                    require_once 'obj/members_roles.obj.php';
                    $members_roles = new Members_Roles();
                    $conn = dbConnect();
                    if ($members_roles->isMemberSwimmer($conn, $_SESSION['username'])) {
                        echo '<br><a href="my-results.php" class="small radius button capitalise h6">My Swim Times</a>
                        <a href="my-pbs.php" class="small radius button capitalise h6">My PBs</a>';
                    }
                    dbClose($conn);
                ?>
            </div>


            <p>Hello <b><?php echo $_SESSION['firstName']; ?></b>, welcome to your member's area.</p>

            <div class="large-12 medium-12 small-12 panel">
                <p>Your last login date was: <b><?php echo  $currentUser->getLastLoginDate();?></b></p>
            </div>

            <p>Below is a list of all the areas of the website you can access.</p>

                <?php
                    include 'obj/roles.obj.php';

                    $member = new Members();
                    $role = new Roles();

                    $conn = dbConnect();

                echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/gala.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Galas</h3>
                    </header>

                    <ul class="nobullet centre">
                        <li><a href="galas.php">View All</a></li>';
                        if (galaFullAccess($connection,$currentUser,$memberValidation))
                        {  echo '<li><a href="galas/create.php">Create</a></li>';
                        }
                    echo '</ul>
                </div>';

                if (galaFullAccess($connection,$currentUser,$memberValidation)){
                    echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/gala.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Gala Events</h3>
                    </header>

                    <ul class="nobullet centre">
                        <li><a href="galas.php">View All</a></li>
                        <li><a href="gala-events/create.php">Create</a></li>
                    </ul>
                </div>

                <div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/gala.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Swim Times</h3>
                    </header>

                    <ul class="nobullet centre">
                        <li><a href="#">View All</a></li>
                        <li><a href="swim-times/create.php">Create</a></li>
                    </ul>
                </div>';
                }

                if (memberViewAccess($connection,$currentUser,$memberValidation)){
                    echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/member.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Members</h3>
                    </header>
                    
                    <ul class="nobullet centre">
                        <li><a href="members.php" role="link">View All</a></li>';
                        if (memberFullAccess($connection,$currentUser,$memberValidation)){
                            echo '<li><a href="members/create.php" role="link">Create</a></li>';
                        }
                    echo '</ul>
                </div>';
                }

                echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/news.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">News</h3>
                    </header>
                    
                    <ul class="nobullet centre">
                        <li><a href="news.php" role="link">View All</a></li>';
                        if(newsFullAccess($connection, $currentUser, $memberValidation)){
                        echo '<li><a href="news/create.php" role="link">Create</a></li>';
                        }
                    echo '</ul>
                </div>';

                echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/shop.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Shop</h3>
                    </header>

                    <ul class="nobullet centre">
                        <li><a href="shop.php">View All</a></li>';
                        if(shopFullAccess($connection, $currentUser, $memberValidation)){
                        echo '<li><a href="shop/create.php">Create</a></li>';
                        }
                    echo '</ul>
                </div>';

                if((squadViewAccess($connection,$currentUser,$memberValidation))){
                    echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/squad.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Squads</h3>
                    </header>
                    <ul class="nobullet centre">
                        <li><a href="squads.php">View All</a></li>';
                    if(squadFullAccess($connection, $currentUser, $memberValidation)) {
                        echo '<li><a href = "squads/create.php" > Create</a ></li>';
                        }
                    echo '</ul>
                </div>';
                }


                echo'<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/timetable.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Timetable</h3>
                    </header>

                    <ul class="nobullet centre">
                        <li><a href="about/timetable.php">View All</a></li>';
                        if(timetableFullAccess($connection, $currentUser, $memberValidation)) {
                            echo '<li><a href="about/timetable/create.php">Create</a></li>';
                        }
                    echo '</ul>
                </div>';



            if(venueViewAccess($connection, $currentUser, $memberValidation)) {
                echo'<div class="panel large-4 medium-6 small-12 columns">
                                <header>
                                    <img src="img/icons/venue.png" alt="" class="middle" width="64px" />
                                    <h3 class="centre">Venues</h3>
                                </header>
                                
                                <ul class="nobullet centre">
                                    <li><a href="venues.php">View All</a></li>';
                            if(venueFullAccess($connection, $currentUser, $memberValidation)){

                            echo' <li><a href="venue/create.php">Create</a></li>';
                            }
                        echo'</ul>
                            </div>';
            }


                if (pagesFullAccess($connection,$currentUser,$memberValidation)){
                    echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/pencil.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Pages</h3>
                    </header>
                    
                    <ul class="nobullet centre">
                        <li><a href="pages/index.php" role="link">View All</a></li>
                        <li><a href="pages/create.php" role="link">Add new Page</a></li>
                    </ul>
                </div>';
                }


                //Gallery
                echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/picture.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Gallery</h3>
                    </header>
                    <ul class="nobullet centre">
                        <li><a href="gallery/">View All</a></li>';
                if(galleryFullAccess($connection, $currentUser, $memberValidation)) {
                    echo '<li><a href = "gallery/create_album.php"> Create Album</a ></li>';
                }
                echo '</ul>
                </div>';


                if (filesFullAccess($connection,$currentUser,$memberValidation)){
                    echo '<div class="panel large-4 medium-6 small-12 columns">
                    <header>
                        <img src="img/icons/cloud-computing-1.png" alt="" class="middle" width="64px" />
                        <h3 class="centre">Files</h3>
                    </header>
                    
                    <ul class="nobullet centre">
                        <li><a href="files/index.php" role="link">View All</a></li>
                        <li><a href="files/upload.php" role="link">Upload</a></li>
                    </ul>
                </div>';
                }

                
        echo'  </div>
            </div>';
    ?>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
