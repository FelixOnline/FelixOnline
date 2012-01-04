<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="keywords" content="felix, student news, student newspaper, felix online, imperial college union, imperial college, felixonline"/>
    <meta name="description" content="Felix Online is the online companion to Felix, the student newspaper of Imperial College London.">
    <meta name="author" content="Jonathan Kim">
    <meta name="google-site-verification" content="V5LPwqv0BzMHvfMOIZvSjjJ-8tJc4Mi1A-L2AEbby50" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base href="<?php echo STANDARD_URL; ?>">

    <!-- Title -->
    <title>
        <?php if(array_key_exists('title', $header)) {
            echo $header['title'];
        } else {
            echo 'Felix Online - The student voice of Imperial College London';
        } ?> 
    </title>

    <!-- Facebook -->
    <meta property="og:site_name" content="Felix Online"/>
    <meta property="fb:page_id" content="206951902659704" />
    <?php 
        if(array_key_exists('meta', $header)) {
            echo $header['meta'];
        } 
    ?>

    <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- CSS files -->
    <?php foreach($this->resources->getCSS() as $key => $value) { ?>
            <link id="<?php echo $key;?>" rel="stylesheet" href="<?php echo $value; ?>">
    <?php } ?>
</head>
<body>
	<div id="topBarCont" class="clearfix">
		<div class="container_16">
			<div id="topBar" class="grid_16">
				<div class="grid_9 links first">
					<ul>
                        <li class="first">
                            <a href="<?php echo STANDARD_URL; ?>" <?php if($this->isPage('frontpage')) echo 'class="selected"';?>>Felix Online</a>
                            </li>
                        <li>
                            <a href="<?php echo STANDARD_URL; ?>media/" <?php if($this->isPage('media')) echo 'class="selected"';?>>Media</a>
                        </li>
                        <li class="last">
                            <a href="<?php echo STANDARD_URL; ?>issuearchive/" <?php if($this->isPage('issuearchive')) echo 'class="selected"';?>>Issue Archive</a>
                        </li>
					</ul>
				</div>
				<div class="grid_3 login omega">
					<?php if(!$currentuser->isLoggedIn()) { ?>
                        <a href="<?php echo Utility::currentPageURL();?>#loginBox" rel="facebox" id="loginButtonA">
                            <div id="loginbutton">Login</div>
                        </a>
                        <?php include($this->directory.'/loginBox.php'); ?>
					<?php } else { ?>
						<div id="loginName">
						<?php //if (get_user_role($uname)>0)
							echo '<a href="/engine/" title="Admin Page"><img src="img/wrench.png"/></a>'; ?>
						<a href="user/<?php echo $uname; ?>/" title="Profile Page"><?php echo get_vname();?></a>
						</div>
						<form method="post" style="display: inline;">
							<input type="submit" value="Logout" id="logoutbutton" name="logout">
						</form>
						<script>
							var user = '<?php echo $uname; ?>';
						</script>
					<? } ?>
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
				<div class="clear"></div>
			</div>
		</div>
	</div>

	<div class="header container_12">
		<!-- Begin header 1 -->
		<div class="grid_5 line">
		</div>
		<div class="grid_2 header1">
			<p>"Keep the Cat Free"</p>
		</div>
		<div class="grid_5 line">
		</div>
		<div class="clear"></div>
		<!-- End header 1 -->

		<!-- Begin header main -->
		<div class="grid_2 date">
			<p><?php echo date('d.m.Y');?></p>
		</div>
		<div class="grid_8 bigFelix">
			<?php if ($_GET['media']) {?>
			<a href="<?php echo STANDARD_URL; ?>media/">
			<?php } else if ($_GET['issuearchive']) { ?>
			<a href="<?php echo STANDARD_URL; ?>issuearchive/">
			<?php } else { ?>
			<a href="<?php echo STANDARD_URL; ?>">
			<?php } ?>
				<h1 <?php if ($_GET['media']) echo 'class="media"'; else if($_GET['issuearchive']) echo 'class="archive"';?>>
					FELIX
				</h1>
			</a>
		</div>
		<div class="grid_2 catPic">
			<img src="img/felix_cat-small.jpg" width="100px" height="110px"/>
		</div>
		<div class="clear"></div>
		<!-- End header main -->

		<!-- Begin header 2 -->
		<div class="grid_3 line">
		</div>
		<div class="grid_6 header2">
			<p>The student voice of Imperial College London since 1949</p>
		</div>
		<div class="grid_3 line">
		</div>
		<div class="clear"></div>
		<!-- End header 2 -->
	</div>
