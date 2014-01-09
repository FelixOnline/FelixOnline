<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js" xmlns:fb="http://ogp.me/ns/fb#"> <!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# feliximperial: http://ogp.me/ns/fb/feliximperial#">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="keywords" content="felix, student news, student newspaper, felix online, imperial college union, imperial college, felixonline"/>
	<meta name="description" content="Felix Online is the online companion to Felix, the student newspaper of Imperial College London.">
	<meta name="author" content="Jonathan Kim">
	<meta name="google-site-verification" content="V5LPwqv0BzMHvfMOIZvSjjJ-8tJc4Mi1A-L2AEbby50" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href='http://fonts.googleapis.com/css?family=Kreon:400,700|Pontano+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

	<base href="<?php echo STANDARD_URL; ?>">

	<?php
		$article = $_GET['article'];
		$header = '';
		if ($article != '') {
			$header .= get_article_title($article).' - '.get_article_category($article).' - ';
		} else if(isset($_GET['cat']) && get_category_label_by_cat($_GET['cat'])) {
			$header .= get_category_label_by_cat($_GET['cat']).' - ';
		} else if(isset($_GET['id']) && check_user($_GET['id'])) {
			$header .= get_vname_by_uname_db($_GET['id']).' - ';
		} else if(isset($_GET['issuearchive'])) {
			$header .= 'Issue Archive - ';
		} else if(isset($_GET['media'])) {
			if($_GET['media']=='photo' && isset($_GET['name']))
				$header .= get_album_name($_GET['name']).' - ';
			if($_GET['media']=='video' && isset($_GET['name']))
				$header .= get_video_name($_GET['name']).' - ';
			$header .= 'Media - ';
		} else {
			$ext = ' - The student voice of Imperial College London';
		}
	
		$header .= 'Felix Online';
		if($ext)
			$header .= $ext;
	?>
	<title><?php echo $header;?></title>

	<meta property="go:site_name" content="Felix Online"/>
	<meta property="fb:app_id" content="200482590030408" />
	<?php
		if(isset($_GET['article'])) { ?>
			<meta property="go:image" content="http://felixonline.co.uk/inc/timthumb.php?src=/<?php echo get_img_uri(get_img_id($article, 1)); ?>&w=100px&zc=1&a=t"/>
			<meta property="go:title" content="<?php echo get_article_title($article); ?>"/>
			<meta property="go:url" content="http://felixonline.co.uk/<?php echo article_url($article); ?>"/>
			<meta property="go:type" content="article"/>
			<meta property="go:description" content="<?php echo get_article_teaser($article);?>"/>
			<meta property="go:locale" content="en_GB"/>
			<meta property="article:section" content="<?php echo get_article_category($article); ?>"/>
	<?php } else if($_GET['media'] == 'video' && isset($_GET['name'])) { ?>
			<meta property="go:description" content="<?php echo get_video_desc($_GET['name']);?>"/>
			<meta property="go:image" content="http://i.ytimg.com/vi/<?php echo get_video_id($_GET['name']); ?>/0.jpg"/>
	<?php } else if($_GET['media'] == 'photo' && isset($_GET['name'])) { ?>
			<?php if(get_album_desc($_GET['name'])) { ?>
				<meta property="go:description" content="<?php echo get_album_desc($_GET['name']);?>"/>
			<?php } ?>
			<meta property="go:title" content="<?php echo get_album_name($_GET['name']).' - Media - Felix Online'; ?>"/>
			<meta property="go:image" content="http://felixonline.co.uk/inc/timthumb.php?src=/gallery/gallery_images/images/<?php echo get_album_image($_GET['name']); ?>&w=50px&zc=1&a=t"/>
	<?php } else { ?>
			<!-- Normal Facebook meta tags -->
			<meta property="go:image" content="http://felixonline.co.uk/img/title.jpg"/>
	<?php } ?>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
	<link rel="shortcut icon" href="favicon.ico">
	
	<!-- CSS : implied media="all" -->
	<link id="main_css" rel="stylesheet" href="css/style.css">
	
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
	<div id="fb-root"></div>
	<div id="topBarCont" class="clearfix">
		<div class="container_16">
			<div id="topBar" class="grid_16 clearfix">
				<div class="grid_9 links first">
					<ul>
						<li class="first"><a href="<?php echo STANDARD_URL; ?>" <?php if(!(isset($_GET['media']) || isset($_GET['issuearchive']))) echo 'class="selected"';?>>Felix Online</a></li>
						<li><a href="<?php echo STANDARD_URL; ?>media/" <?php if(isset($_GET['media'])) echo 'class="selected"';?>>Media</a></li>
						<li class="last"><a href="<?php echo STANDARD_URL; ?>issuearchive/" <?php if(isset($_GET['issuearchive'])) echo 'class="selected"';?>>Issue Archive</a></li>
						<!--<li><a href="publications/">Other Publications</a></li>
						<li class="last"><a href="http://m.felixonline.co.uk">Mobile</a></li>-->
					</ul>
				</div>
				<div class="grid_3 login omega">
					<?php if(!is_logged_in()) { ?>
							<a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox" id="loginButtonA"><div id="loginbutton">Login</div></a>
							<div id="loginBox">
								<?php echo '<form action="'.AUTHENTICATION_PATH."?session=".$_SESSION["felix"]["name"]."&goto=".str_replace(array("&login=FAIL",$session_param1,$session_param2),array('','',''),curPageURLNonSecure()).'" id="loginForm" method="post">';?>
									<h3>Login to Felix Online</h3>
									<table>
										<tr>
											<td><label for="user">IC Username: </label></td>
											<td><input type="text" name="username" id="user"/></td>
										</tr>
										<tr>
											<td><label for="password">IC Password: </label></td>
											<td><input type="password" name="password" id="password"/></td>
										</tr>
										<tr>
											<td><label for="remember">Remember Me: </label></td>
											<td><input type="checkbox" name="remember" id="rememberButton" value="remember me" checked="checked" /></td>
										</tr>
										<tr>
											<td></td><td><input type="submit" value="Login (SSL)" name="login" id="submit"/></td>
										</tr>
									</table>
								</form>
							</div>
						<?php } else {
							$uname = is_logged_in();
							?>
							<div id="loginName">
							<?php if (get_user_role($uname)>0)
								echo '<a href="/engine/" title="Admin Page"><img src="img/wrench.png"/></a>'; ?>
							<a href="user/<?php echo $uname; ?>/" title="Profile Page"><?php echo get_vname();?></a>
							</div>
							<form method="post" style="display: inline;">
								<input type="submit" value="Logout" id="logoutbutton" name="logout">
							</form>
							<script>
								var user = '<?php echo $uname; ?>';
							</script>
						<?php } ?>
					</div>
					<div class="grid_4 last" id="searchBoxCont">
						<form action="search/" id="cse-search-box">
							<?php
								if($_GET["q"] && $_GET["q"] != 'Search Felix Online...') { ?>
									<input type="text" name="q" size="31" id="searchBox" autocomplete="off" value="<?php echo $_GET['q'];?>"/>
							<?php } else { ?>
									<input type="text" name="q" size="31" id="searchBox" class="faded" autocomplete="off" onclick="if(this.value == 'Search Felix Online...') {this.value=''; this.style.color='#222';}" onblur="if(this.value.length == 0){ this.value='Search Felix Online...'; this.style.color='#999';};" value="Search Felix Online..."/>
							<?php } ?>
							<div class="clear"></div>
							<input type="submit" name="sa" value="" id="searchButton"/>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="header container_12">
		<!-- Begin header 1 -->
		<div class="grid_12 bigFelix">
			<?php if ($_GET['media']) {?>
			<a href="<?php echo STANDARD_URL; ?>media/">
			<?php } else if ($_GET['issue archive']) { ?>
			<a href="<?php echo STANDARD_URL; ?>issuearchive/">
			<?php } else { ?>
			<a href="<?php echo STANDARD_URL; ?>">
			<?php } ?>
				<h1 <?php if ($_GET['media']) echo 'class="media"'; else if($_GET['issuearchive']) echo 'class="archive"';?>>
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
