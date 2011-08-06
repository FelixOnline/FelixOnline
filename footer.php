<!-- Footer -->
<div class="container_12">
	<div class="grid_12 footer">
		<div class="grid_6 alpha">
			<img src="img/title-small.jpg"/>
		</div>
		<div class="grid_6 details alpha">
			<p>Felix, Beit Quad, Prince Consort Road, London SW7 2BB</p>
			<p>Email: <?php echo hide_email('felix@imperial.ac.uk');?> Tel: 020 7594 8072 Fax: 020 7594 8065</p>
			<p>Webdesign by <a href="http://felixonline.co.uk/user/jk708/">Jonathan Kim</a> and <a href="http://www.cjbirkett.co.uk/" target="_BLANK">Chris Birkett</a></p>
			<p>&copy; Felix Imperial <?php echo romanNumerals(date('Y')); ?> <a href="#topBarCont">Top of page</a></p>
		</div>
		<div class="clear"></div>
	</div>
</div>

  <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.5.2.min.js"%3E%3C/script%3E'))</script>
  
  <?php if ($_GET['media'] == 'photo') { ?>
		<script src="js/galleria/galleria-1.2.2.min.js" type="text/javascript"></script>
		<script>
			Galleria.loadTheme('/js/galleria/themes/classic/galleria.classic.min.js');
			$(".photoSlideshow").galleria({
				width: 940,
				height: 510,
				showInfo: true,
				autoplay: true,
				thumbnails: false
			});
		</script>
	<?php } ?>
	
  <!-- scripts concatenated and minified via ant build script-->
  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>
  <!-- end concatenated and minified scripts-->
  
	<?php if ($_GET['article']) { ?>
	<!-- Digg button -->
	<script type="text/javascript">
			(function() {
			var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
			s.type = 'text/javascript';
			s.async = true;
			s.src = 'http://widgets.digg.com/buttons.js';
			s1.parentNode.insertBefore(s, s1);
			})();
	  </script>
	   <script src="js/galleria/galleria-1.2.2.min.js" type="text/javascript"></script>
	  <script>
		Galleria.loadTheme('/js/galleria/themes/classic/galleria.classic.min.js');
		$("#photogallery").galleria({
				width: 940,
				height: 510,
				showInfo: true,
				autoplay: true,
				thumbnails: false
			}).show();
		</script>
	  <!-- Google +1 -->
	  <script type="text/javascript" src="http://apis.google.com/js/plusone.js">
		{lang: 'en-GB'}
	  </script>
	 <?php } ?>

  <!--[if lt IE 7 ]>
    <script src="js/libs/dd_belatedpng.js"></script>
    <script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
  <![endif]-->

  <!-- put this code at the bottom of the page -->
<?php
      $mtime = microtime();
      $mtime = explode(" ", $mtime);
      $mtime = $mtime[1] + $mtime[0];
      $endtime = $mtime;
      $totaltime = ($endtime - $starttime);
      echo '<!--This page was created in ' .$totaltime. ' seconds.-->';
?>

<?php
	echo '<!-- Query string: '.$_SERVER['QUERY_STRING'].' Request URI: '.$_SERVER['REQUEST_URI'].' -->';
?>

</body>
</html>