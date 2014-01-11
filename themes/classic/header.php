<!DOCTYPE html>

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
	<meta name="author" content="Jonathan Kim">
	<meta name="google-site-verification" content="V5LPwqv0BzMHvfMOIZvSjjJ-8tJc4Mi1A-L2AEbby50" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href='//fonts.googleapis.com/css?family=Kreon:400,700|Bevan|Pontano+Sans' rel='stylesheet' type='text/css'>

	<base href="<?php echo STANDARD_URL; ?>">

	<!-- Title -->
	<title>
		<?php if($title) {
			echo $title;
		} else {
			echo 'Felix Online - The student voice of Imperial College London';
		} ?> 
	</title>

	<!-- Facebook -->
	<meta property="og:site_name" content="Felix Online"/>
	<meta property="fb:app_id" content="200482590030408" />
	<?php 
		if($meta) {
			echo $meta;
		} 
	?>

	<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
	<link rel="shortcut icon" href="favicon.ico">
	<!-- CSS files -->
	<?php foreach($theme->resources->getCSS() as $key => $value) { ?>
			<link id="<?php echo $key;?>" rel="stylesheet" href="<?php echo $value; ?>">
	<?php } ?>

	<script src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>js/libs/modernizr-2.5.3.min.js"></script>
</head>
<body>
	<div id="fb-root"></div>
	<div id="topBarCont" class="clearfix">
		<div class="container_16">
			<div id="topBar" class="grid_16 clearfix">
				<div class="grid_9 links first">
					<ul class="clearfix">
						<li class="first">
							<a href="<?php echo STANDARD_URL; ?>" <?php if($theme->isSite('main')) echo 'class="selected"';?>>Felix Online</a>
							</li>
						<li>
							<a href="<?php echo STANDARD_URL; ?>media/" <?php if($theme->isSite('media')) echo 'class="selected"';?>>Media</a>
						</li>
						<li class="last">
							<a href="<?php echo STANDARD_URL; ?>issuearchive/" <?php if($theme->isSite('archive')) echo 'class="selected"';?>>Issue Archive</a>
						</li>
					</ul>
				</div>
				<div class="grid_3 login omega">
					<?php if(!$currentuser->isLoggedIn()) { ?>
						<a href="<?php echo Utility::currentPageURL();?>#loginBoxCont" rel="facebox" id="loginButtonA">
							<div id="loginbutton">Login</div>
						</a>
						<div id="loginBoxCont">
							<?php $theme->render('loginBox'); ?>
						</div>
					<?php } else { ?>
						<div id="loginName">
						<?php if ($currentuser->getRole() > 0)
							echo '<a href="'.STANDARD_URL.'engine/" title="Admin Page"><img src="img/wrench.png"/></a>'; ?>
							<a href="<?php echo $currentuser->getURL(); ?>" title="Profile Page">
								<?php echo $currentuser->getName();?>
							</a>
						</div>
						<form method="post" action="<?php echo STANDARD_URL.'logout/?goto='.Utility::currentPageURL(); ?>" style="display: inline;">
							<input type="submit" value="Logout" id="logoutbutton" name="logout">
						</form>
						<script>
							var user = '<?php echo $currentuser->getUser(); ?>';
						</script>
					<?php } ?>
				</div>
				<div class="grid_4 last" id="searchBoxCont">
					<form action="search/" id="cse-search-box">
						<?php
							if(isset($_GET["q"]) && $_GET["q"]) { ?>
								<input type="text" name="q" size="31" id="searchBox" autocomplete="off" value="<?php echo $_GET['q'];?>"/>
						<?php } else { ?>
								<input type="text" name="q" size="31" id="searchBox" autocomplete="off" placeholder="Search Felix Online..."/>
						<?php } ?>
						<div class="clear"></div>
						<input type="submit" value="" id="searchButton"/>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="header container_12">
		<!-- Begin header 1 -->
		<div class="grid_12 bigFelix">
			<?php if ($theme->isSite('media')) {?>
			<a href="<?php echo STANDARD_URL; ?>media/">
			<?php } else if ($theme->isSite('archive')) { ?>
			<a href="<?php echo STANDARD_URL; ?>issuearchive/">
			<?php } else { ?>
			<a href="<?php echo STANDARD_URL; ?>">
			<?php } ?>
				<h1 <?php if ($theme->isSite('media')) echo 'class="media"'; else if($theme->isSite('archive')) echo 'class="archive"';?>>
					FELIX
				</h1>
			</a>
			<div class="catPic"><img src="img/felix_cat-small.jpg" alt="" /></div>
			<div class="headerText">
				"Keep the Cat Free"
				<div class="date">
					<?php echo date('d/m/y', time()); ?>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Navigation -->
	<?php 
		if($theme->isSite('main')) {
			$theme->render('navigation'); 
		}
	?>
	<!-- End of navigation -->
