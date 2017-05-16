<meta charset="utf-8"><meta charset="utf-8" class="foundation-data-attribute-namespace"><meta charset="utf-8" class="foundation-mq-xxlarge"><meta  charset="utf-8" class="foundation-mq-xlarge-only"><meta charset="utf-8" class="foundation-mq-xlarge"><meta charset="utf-8" class="foundation-mq-large-only"><meta charset="utf-8" class="foundation-mq-large"><meta charset="utf-8" class="foundation-mq-medium-only"><meta charset="utf-8" class="foundation-mq-medium"><meta charset="utf-8" class="foundation-mq-small-only"><meta charset="utf-8" class="foundation-mq-small"><meta charset="utf-8" class="foundation-mq-topbar">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="keywords" content="swimming, competitve, non-competitive, swim, club, learn, coaching, freestyle, butterfly, breaststroke, backcrawl, BASC, Bucksburn, Aberdeen, Aberdeenshire, Scotland">
<meta name="description" content="Bucksburn Amateur Swimming Club (BASC) is a swimming club based in Aberdeen and aims to provide an opportunity for all individauls to reach their full potential, whether through competition or not.">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>

<!--highslide js START-->
<script type="text/javascript" src="<?php echo $domain ?>/highslide/highslide-with-gallery.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $domain ?>/highslide/highslide.css"/>

<!--
    2) Optionally override the settings defined at the top
    of the highslide.js file. The parameter hs.graphicsDir is important!
-->

<script type="text/javascript">
    hs.graphicsDir = '<?php echo $domain ?>/highslide/graphics/';
    hs.align = 'center';
    hs.transitions = ['expand', 'crossfade'];
    hs.outlineType = 'rounded-white';
    hs.fadeInOut = true;
    //hs.dimmingOpacity = 0.75;

    // Add the controlbar
    hs.addSlideshow({
        //slideshowGroup: 'group1',
        interval: 5000,
        repeat: false,
        useControls: true,
        fixedControls: 'fit',
        overlayOptions: {
            opacity: .75,
            position: 'bottom center',
            hideOnMouseOut: true
        }
    });
</script>
<!--highslide js END-->

<?php
//Relative paths for local js files
//foundation
if(file_exists('js/foundation.min.js')) {
    echo'<script src="js/foundation.min.js"></script>
        <script src="js/functions.js"></script>';
} elseif(file_exists('../js/foundation.min.js')) {
    echo'<script src="../js/foundation.min.js"></script>';
}
elseif(file_exists('../../js/foundation.min.js')) {
    echo'<script src="../../js/foundation.min.js"></script>';
}

//custom functions
if(file_exists('js/functions.js')) {
    echo'<script src="js/functions.js"></script>
        <script src="js/functions.js"></script>';
} elseif(file_exists('../js/functions.js')) {
    echo'<script src="../js/functions.js"></script>';
}
elseif(file_exists('../../js/functions.js')) {
    echo'<script src="../../js/functions.js"></script>';
}
?>

