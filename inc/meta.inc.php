<meta charset="utf-8"><meta charset="utf-8" class="foundation-data-attribute-namespace"><meta charset="utf-8" class="foundation-mq-xxlarge"><meta  charset="utf-8" class="foundation-mq-xlarge-only"><meta charset="utf-8" class="foundation-mq-xlarge"><meta charset="utf-8" class="foundation-mq-large-only"><meta charset="utf-8" class="foundation-mq-large"><meta charset="utf-8" class="foundation-mq-medium-only"><meta charset="utf-8" class="foundation-mq-medium"><meta charset="utf-8" class="foundation-mq-small-only"><meta charset="utf-8" class="foundation-mq-small"><meta charset="utf-8" class="foundation-mq-topbar">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="keywords" content="swimming, competitive, non-competitive, swim, club, learn, coaching, freestyle, butterfly, breaststroke, backcrawl, BASC, Bucksburn, Aberdeen, Aberdeenshire, Scotland">
<meta name="description" content="Bucksburn Amateur Swimming Club (BASC) is a swimming club based in Aberdeen and aims to provide an opportunity for all individauls to reach their full potential, whether through competition or not.">
<script defer src="https://code.jquery.com/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script defer src="https://code.jquery.com/ui/1.10.0/jquery-ui.min.js"></script>

<!--<!--Jquery-->
<!--<script src="https://code.jquery.com/jquery-1.9.0.min.js"></script>-->
<!--<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>-->
<!--<link  href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>-->


<!--Main Site CSS-->
<link href="<?php echo $domain ?>css/site.min.css" rel="stylesheet"/>

<!--highslide js START-->
<script type="text/javascript" src="<?php echo $domain ?>highslide/highslide-with-gallery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $domain ?>highslide/highslide.min.css"/>

<!--Slick.js START-->
<link rel="stylesheet" type="text/css" href="<?php echo $domain ?>css/slick/slick.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $domain ?>css/slick/slick-theme.min.css">

<!--Favicon code START-->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $domain ?>apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $domain ?>favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>favicon-16x16.png">
<link rel="manifest" href="<?php echo $domain ?>manifest.json">
<link rel="mask-icon" href="<?php echo $domain ?>safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">

<!--Favicon codeEND -->

<!--
    2) Optionally override the settings defined at the top
    of the highslide.js file. The parameter hs.graphicsDir is important!
-->

<script type="text/javascript">
    hs.graphicsDir = '<?php echo $domain ?>highslide/graphics/';
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

<script  defer type="text/javascript" src="<?php echo $domain ?>js/foundation.min.js"></script>
<script  defer type="text/javascript" src="<?php echo $domain ?>js/functions.js"></script>

<script type="javascript">
    $(document).foundation();
</script>

<?php
//Relative paths for local js files
//foundation

//
//if(file_exists('js/foundation.min.js')) {
//    echo'<script src="js/foundation.min.js"></script>
//        <script src="js/functions.js"></script>';
//} elseif(file_exists('../js/foundation.min.js')) {
//    echo'<script src="../js/foundation.min.js"></script>';
//}
//elseif(file_exists('../../js/foundation.min.js')) {
//    echo'<script src="../../js/foundation.min.js"></script>';
//}
//
//
////custom functions
//if(file_exists('js/functions.js')) {
//    echo'<script src="js/functions.js"></script>
//        <script src="js/functions.js"></script>';
//} elseif(file_exists('../js/functions.js')) {
//    echo'<script src="../js/functions.js"></script>';
//}
//elseif(file_exists('../../js/functions.js')) {
//    echo'<script src="../../js/functions.js"></script>';
//}
?>



