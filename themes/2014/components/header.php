<!doctype html>
	<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if IE 7 ]>	<html lang="en" class="no-js ie7" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if IE 8 ]>	<html lang="en" class="no-js ie8" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if IE 9 ]>	<html lang="en" class="no-js ie9" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# feliximperial: http://ogp.me/ns/fb/feliximperial#">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="keywords" content="felix, student news, student newspaper, felix online, imperial college union, imperial college, felixonline"/>
		<meta name="description" content="Felix Online is the online companion to Felix, the student newspaper of Imperial College London.">
		<meta name="google-site-verification" content="V5LPwqv0BzMHvfMOIZvSjjJ-8tJc4Mi1A-L2AEbby50" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<base href="<?php echo STANDARD_URL; ?>">
		<link href='https://fonts.googleapis.com/css?family=Alegreya+Sans:400,700,400italic,700italic|Noto+Serif:400,700,400italic,700italic|Sorts+Mill+Goudy:400,400italic' rel='stylesheet' type='text/css'>
		<script src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>js/vendor/modernizr.js"></script>

		<?php
			if (isset($meta) && $meta) {
				echo $meta;
			}
		?>

		<meta property="og:site_name" content="Felix Online"/>
		<meta property="fb:app_id" content="200482590030408" />

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
				echo 'Felix Online - The student voice of Imperial College London';
			} ?> 
		</title>
	</head>
	<body>
<?php
	// If article page
	if ($theme->isPage('article')) {
		$check = $article->getCategory()->getCat();
	} else if ($theme->isPage('category')) { // if category page
		$check = $category->getCat();
	} else {
		$check = 'home';
	}
?>
		<div id="fb-root"></div>
		<div class="felix-header felix-header-<?php echo $check; ?>">
			<div class="row">
				<div class="medium-6 columns felix-header-actions">
					<?php echo date("l jS F"); ?> • <a href="<?php echo STANDARD_URL; ?>issuearchive/">Issue Archive</a>
					<?php if ($currentuser->isLoggedIn() && $currentuser->getRole() > 0) {
						echo ' • <a href="'.STANDARD_URL.'engine/">Author Zone</a>'; ?>
					<?php } ?>
				</div>
				<div class="medium-6 columns text-right felix-header-actions">
					<?php if(!$currentuser->isLoggedIn()) { ?>
						<a href="#" data-reveal-id="loginModal">Log in</a>
					<?php } else { ?>
						<a href="<?php echo $currentuser->getURL(); ?>" title="Profile Page">
							<?php echo $currentuser->getName();?>
						</a> • 
						<a href="<?php echo STANDARD_URL.'auth/?logout&goto='.Utility::currentPageURL(); ?>">Log out</a>
					<?php } ?> • <a href="<?php echo STANDARD_URL; ?>contact">Contact us</a><!-- • Advertising • About us -->
				</div>
			</div>
		</div>

		<div class="felix-title felix-title-<?php echo $check; ?>">
			<div class="row">
				<div class="medium-8 columns felix-title-logo">
					<div>
						<a href="<?php echo STANDARD_URL; ?>">
							<img src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/black logo.png" alt=""> 
							<h1>Felix Online</h1>
						</a>
					</div>
				</div>
				<div class="medium-4 columns text-right felix-buttons">
					<p><a href="https://www.facebook.com/FelixImperial"><img alt="Facebook" src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/fb.png" class="felix-header-icon"></a> <a href="https://twitter.com/feliximperial"><img alt="Twitter" src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/twitter.png" class="felix-header-icon"></a> <a href="<?php echo STANDARD_URL; ?>contact"><img alt="Contact Us" src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/email.png" class="felix-header-icon"></a> <a href="<?php echo STANDARD_URL.'rss'; ?>"><img alt="RSS Feed" src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/rss.png" class="felix-header-icon"></a></p>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-8 columns">
					<span class="felix-subtitle show-for-medium-up">The official website of Imperial's student newspaper</span>
				</div>
				<div class="medium-4 columns felix-search">
					<form action="search/" method="get">
						<input type="search" required name="q" placeholder="Type something to search and press enter..." class="felix-search-box">
					</form>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="small-12 columns">
				<?php if(!$currentuser->isLoggedIn()) {
					$theme->render('components/loginBox');
				} else { ?>
				<script>
					var user = '<?php echo $currentuser->getUser(); ?>';
				</script>
				<?php } ?>
			</div>
		</div>

	<!-- Navigation -->
	<?php 
		if($theme->isSite('main')) {
			$theme->render('components/navigation'); 
		}
	?>
	<!-- End of navigation -->
