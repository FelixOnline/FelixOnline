<!doctype html>
	<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if IE 7 ]>	<html lang="en" class="no-js ie7" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if IE 8 ]>	<html lang="en" class="no-js ie8" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if IE 9 ]>	<html lang="en" class="no-js ie9" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="keywords" content="<?php echo \FelixOnline\Core\Settings::get('site_keywords'); ?>"/>
		<meta name="description" content="<?php echo \FelixOnline\Core\Settings::get('site_description'); ?>">
		<meta name="google-site-verification" content="<?php echo \FelixOnline\Core\Settings::get('app_google'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<base href="<?php echo STANDARD_URL; ?>">
		<script src="<?php echo STANDARD_URL.'themes/modern/'; ?>js/modernizr.js"></script>

		<?php
			if (isset($meta) && $meta) {
				echo $meta;
			}

			if($title && (!$meta || strpos($meta, 'og:title') === false)) {
				echo '<meta property="og:title" content="'.$title.'"/>';
			}
		?>

		<meta property="og:site_name" content="<?php echo \FelixOnline\Core\Settings::get('site_name'); ?>"/>
		<meta property="fb:app_id" content="<?php echo \FelixOnline\Core\Settings::get('app_fb'); ?>" />

		<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="favicon.ico">
		<!-- CSS files -->
		<?php foreach($theme->resources->getCSS() as $key => $value) { ?>
			<link id="<?php echo $key;?>" rel="stylesheet" href="<?php echo $value; ?>">
		<?php } ?>

		<title>
			<?php if($title) {
				echo $title;
			} else {
				echo (\FelixOnline\Core\Settings::get('site_name')).' - '.(\FelixOnline\Core\Settings::get('site_tagline'));
			} ?> 
		</title>

		<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
		<script type="text/javascript">
			window.cookieconsent_options = {"message":"We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.","dismiss":"Got it!","learnMore":"More info","link":null,"theme":"dark-bottom"};
		</script>

		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
		<!-- End Cookie Consent plugin -->
	</head>
	<body>
		<div id="fb-root"></div>

	<?php
		if(isset($article)) {
			$theme->render('components/helpers/block_advert', array('frontpage' => false, 'article' => $article, 'category' => false));
		} elseif(isset($category)) {
			$theme->render('components/helpers/block_advert', array('frontpage' => false, 'article' => false, 'category' => $category));
		} else {
			$theme->render('components/helpers/block_advert', array('frontpage' => true, 'article' => false, 'category' => false));
		}
	?>

	<?php 
		$theme->render('components/globals/navigation'); 
	?>
