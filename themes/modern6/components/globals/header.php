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
	</head>
	<body>
	<?php 
		$theme->render('components/globals/navigation');
	?>
