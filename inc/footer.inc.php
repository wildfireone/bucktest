<footer class="footer clearfix">
	<div class="large-12 medium-12 small-12 columns">
	    <div class="row">
	    <br>
		    <span class="copyright centre middle">© <?php echo date("Y"); ?> Bucksburn Amateur Swimming Club</span>
	    </div>
	</div>
</footer>
<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo $domain ?>/css/slick/slick.min.js" type="text/javascript" charset="utf-8"></script>
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
