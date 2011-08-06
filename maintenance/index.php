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

  <title>Felix Online - Coming Soon</title>
  <meta name="keywords" content="felix, student news, student newspaper, felix online, imperial college union, imperial college, felixonline"/>
  <meta name="description" content="Felix Online is the online companion to Felix, the student newspaper of Imperial College London.">
  <meta name="author" content="Jonathan Kim">
	
  <!-- Facebook meta -->
  <meta property="og:image" content="http://felixonline.co.uk/maintenance/img/title-medium.jpg"/> 
  <meta property="og:url" content="http://felixonline.co.uk/"/> 
  <meta property="og:type" content="website"/> 
  <meta property="og:description" content="The brand new Felix Online is coming on April the 25th"/> 
  <meta property="fb:admins" content="560966291" />
  
  <link rel="shortcut icon" href="/favicon.ico">

  <!-- CSS -->
  <link rel="stylesheet" href="/maintenance/css/style.min.css">
	
  <!-- Google Analytics -->
  <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-12220150-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
  </script>
</head>

<body>

  <div id="container" class="container_12">
    <header class="grid_8 push_2">
		<h1>Felix</h1>
    </header>
    <div id="main" role="main">
		<div id="coming" class="grid_8 push_2">
			<h2>Coming 25<span>th</span> April...</h2>
			<div id="buttons">
				<div id="facebook">
					<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="" layout="button_count" show_faces="false" width="90" font="arial"></fb:like>
				</div>
				<div id="twitter">
					<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="feliximperial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				</div>
			</div>
			<div id="counter"><!-- Insert Counter Here --></div>
			<div class="desc"> 
				<div>Days</div> 
				<div>Hours</div> 
				<div>Minutes</div> 
				<div>Seconds</div> 
				<span style="clear:both; display:block;"></span>
			</div> 
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div id="info" class="grid_8 push_2">
			<p>A brand new Felix Online is coming soon. Until that time enjoy this entirely unrepresentative picture of us at work or just watch the pretty numbers count down until launch.</p>
			<img src="/maintenance/img/cat-pet-laptop.jpg"/>
		</div>
		<div class="clear"></div>
		<div id="emailsignup" class="grid_8 push_2">
			<p>If you would like to receive an email when everything is ready then just add it to this lovely box:</p>
			<form id="emailform" method="post" action="">
				<div id="emailinput">
					<input type="text" id="email" name="email" class="required email" placeholder="Your email address"/>
				</div>
				<div id="emailsubmit">
					<input type="submit" id="submit" name="emailsubmit" value="Update Me"/>
					<span class="loading">Adding...</span>
				</div>
				<img src="/maintenance/img/cancel.png" style="display: none;"/>
				<div class="clear"></div>				
			</form>
		</div>
		<div id="emailsuccess" class="grid_8 push_2">
			<p>Thank you!</p>
			<img src="/maintenance/img/win.jpg"/>
		</div>
		<div id="otherlinks" class="grid_8 push_2">
			<p>Or if you are too cool for emails then you can find out more at the following places:</p>
			<ul>
				<a href="http://www.facebook.com/FelixImperial" title="Facebook Page"><li id="facebook">&nbsp;</li></a>
				<a href="http://twitter.com/feliximperial" title="Twitter Account"><li id="twitter">&nbsp;</li></a>
				<a href="mailto:felix@imperial.ac.uk" title="Email Us"><li id="contact">&nbsp;</li></a>
			</ul>
		</div>
		<div class="clear"></div>
    </div>
	<?php
		function romanNumerals($num) {
			$n = intval($num);
			$res = '';
		 
			/*** roman_numerals array  ***/
			$roman_numerals = array(
						'M'  => 1000,
						'CM' => 900,
						'D'  => 500,
						'CD' => 400,
						'C'  => 100,
						'XC' => 90,
						'L'  => 50,
						'XL' => 40,
						'X'  => 10,
						'IX' => 9,
						'V'  => 5,
						'IV' => 4,
						'I'  => 1);
		 
			foreach ($roman_numerals as $roman => $number) 
			{
				/*** divide to get  matches ***/
				$matches = intval($n / $number);
		 
				/*** assign the roman char * $matches ***/
				$res .= str_repeat($roman, $matches);
		 
				/*** substract from the number ***/
				$n = $n % $number;
			}
		 
			/*** return the res ***/
			return $res;
		}
	?>
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