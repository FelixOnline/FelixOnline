<?php
	require_once('../preview/inc/common.inc.php');
	
	if(!($_COOKIE['felixonline'] == 'jk708' || $_COOKIE['felixonline'] == 'felix' || $_COOKIE['felixonline'] == 'ks607' || $_COOKIE['felixonline'] == 'cjb07/')) {
		header('Location: http://www.felixonline.co.uk/');
		exit;
	} 
?>

<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">

  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
       Remove this if you use the .htaccess -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <title>Felix Online - Email List</title>
	
  <link rel="shortcut icon" href="/favicon.ico">

  <!-- CSS -->
  <link rel="stylesheet" href="/maintenance/css/style.css">
	
</head>

<body>

  <div id="container" class="container_12">
    <header class="grid_8 push_2">
		<h2>Email List</h2>
    </header>
	<div class="clear"></div>
    <div id="main" role="main" class="grid_8 push_2">
		<?php
			$sql = "SELECT * FROM preview_email ORDER BY id";
			$result = mysql_query($sql);
			$num = mysql_num_rows($result);
		?>
		<p><?php echo $num;?> emails</p>
		<ul style="height: 100%">
		<?php
			
			while($row = mysql_fetch_array($result)){
		?>
			<li><?php echo $row['email'];?></li>
		<?php } ?>
		</ul>
    </div>
	<div class="clear"></div>
    <footer>
		<p>Design by <a href="mailto:jkimbo@gmail.com">Jonathan Kim</a></p>
		<p>&copy; Felix Imperial <?php echo romanNumerals(date('Y')); ?></p>
    </footer>
	<div class="clear"></div>
  </div> <!--! end of #container -->

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
  <script>window.jQuery || document.write("<script src='/maintenance/js/libs/jquery-1.5.1.min.js'>\x3C/script>")</script>

  <script src="/maintenance/js/plugins.js"></script>
  <script src="/maintenance/js/script.js"></script>


  <!--[if lt IE 7 ]>
    <script src="/maintenancejs/libs/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix("img, .png_bg"); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
  <![endif]-->

</body>
</html>