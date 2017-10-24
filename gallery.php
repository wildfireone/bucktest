<?php
    session_start();
    require 'inc/connection.inc.php';
    require 'inc/security.inc.php';
?>

<!DOCTYPE html>
<html lang="en-GB">
    
<head>    
    <?php include 'inc/meta.inc.php';?>
    <title>Gallery | Bucksburn Amateur Swimming Club</title>
    <link href='https://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Hind' rel='stylesheet' type='text/css'>   
    <link href="css/site.min.css" rel="stylesheet"/>
    <link href="css/lightbox.css" rel="stylesheet" />
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/lightbox.min.js"></script>
</head>

<body>   
    <?php include 'inc/header.inc.php'; ?>   
    <br>
    <div class="row" id="content">
        <div class="large-12 medium-12 small-12 columns">
        
        <ul class="breadcrumbs">
            <li><a href="index.php" role="link">Home</a></li>
            <li class="current">Gallery</li>
        </ul>
    
        <h2>Gallery</h2>

        <div id="gallery">
            <div style="position: relative; padding-bottom: 60%; overflow: auto; -webkit-overflow-scrolling:touch;"><iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://flickrembed.com/cms_embed.php?source=flickr&layout=responsive&input=www.flickr.com/photos/131625432@N06/&sort=0&by=user&theme=default&scale=fit&skin=default&id=582f13ab7f0b2" scrolling="no" frameborder="0" allowFullScreen="true"></iframe></div><small style="display: block; text-align: center; margin: 0 auto;">Created with <a href="https://flickrembed.com">flickr embed</a>.</small>
        </div>

<!--        <script>-->
<!--            (function() {-->
<!--              //var flickerAPI = "https://api.flickr.com/services/rest/?method=flickr.people.getPhotos&api_key=1d23e90f286f10e755e2a674828281e9&user_id=131625432%40N06&format=json&nojsoncallback=1&auth_token=72157649439428724-711289c517c6b18f&api_sig=6f34f12214649aa0132997a031bab359";-->
<!--              var flickerAPI = "https://api.flickr.com/services/rest/?method=flickr.people.getPhotos&api_key=029cde023d59092b6abbecd9c49a6464&user_id=131625432%40N06&format=json&nojsoncallback=1";-->
<!--              //, {-->
<!--              //  api_key: "131625432@N06",-->
<!--              //  user_id: "029cde023d59092b6abbecd9c49a6464",-->
<!--              //  format: "json",-->
<!--              //  nojsoncallback: "1"-->
<!--              //}-->
<!--              $.getJSON(flickerAPI)-->
<!--                .done(function(data) {-->
<!--                  $.each(data.photos.photo, function(i, item) {-->
<!--                    out = '';-->
<!--                    out += '<div class="large-3 medium-4 small-6 columns photo">';-->
<!--                    out += '<a href="' + url(item.farm, item.server, item.id, item.secret) + '" data-lightbox="' + item.id + '" data-title="' + item.title + '"><img src="' + url(item.farm, item.server, item.id, item.secret) + '" alt="' + item.title + '" />' + item.title + '</a>';-->
<!--                    out += '</div>'-->
<!--                    $("#gallery").append(out);-->
<!--                  });-->
<!--                });-->
<!--            })();-->
<!---->
<!--            function url (farm,server,id,secret){-->
<!--                return 'https://farm' + farm + '.staticflickr.com/' + server + '/' + id + '_' + secret + '.jpg';-->
<!--            }-->
<!--        </script>-->
        
        </div>
    </div>
    <?php include 'inc/footer.inc.php';?>
</body>

</html>
