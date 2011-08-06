<?php require_once('inc/common.inc.php'); ?>

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
  <?php 
	$article = $_GET['article'];
	
	$header = get_article_title($article).' - '.get_article_category($article).' - ';
	$header .= 'Felix Online';
  ?>
  <title><?php echo $header;?></title>
  <base href="http://felixonline.co.uk/">
  <meta name="description" content="Felix Online is the online companion to Felix, the student newspaper of Imperial College London.">
  <meta name="author" content="Jonathan Kim">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
  <link rel="shortcut icon" href="favicon.ico">
  
  <!-- CSS : implied media="all" -->
  <link rel="stylesheet" href="css/print.css">
	
</head>
<body>

	<!--
		Resources:
			http://line25.com/tutorials/handy-tips-for-creating-a-print-css-stylesheet
			http://css-tricks.com/css-tricks-finally-gets-a-print-stylesheet/
			http://stackoverflow.com/questions/320357/safe-width-in-pixel-for-printing-web-pages
			http://www.alistapart.com/articles/goingtoprint/
			
		Examples:
			http://www.vanityfair.com/online/oscars/2011/04/the-jerry-weintraub-way.html?printable=true
			http://www.time.com/time/printout/0,8816,2062617,00.html
	
	-->
	
	<!-- 
		Style:
			width: 650px (vanity fair)
			font-family: times-new-roman, serif; (or Georgia?)
	
	-->
<div id="page">
	<div id="actions">
		<a href="javascript:window.print();">Click to print</a>
	</div>
	
	<!-- Header - Felix header without the date -->
	<div id="header">
		<img src="img/title.jpg" width="250px"/>
		<p id="text">The student voice of Imperial College London since 1949</p>
		<div style="clear: both"></div>
	</div>
	
	<!-- Article header -->
	<h1><?php echo get_article_title($article);?></h1>

	<!-- Sub header -->
	<p class="subheader"><?php echo get_article_teaser($article);?></p>
	
	<!-- Article Description - authors, category, date -->
	<div class="articleInfo">
		<p><?php echo output_in_english_authors(get_article_authors_uname($article)); ?></p>
		<p><span class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></span> - <?php echo date("l F j, Y", get_article_date($article));?></p>
	</div>
	
	<!-- Article picture -->
	<?php 
		$image = get_img_id($article, 1);
		if ($image != ''){
			if ($image == 183 || $image == 742) {
			} else {?>
			<?php
				$image = get_img_id($article, 1);
				if($image) {
					$size = getimagesize(get_img_uri($image)); // $size[0] = width, $size[1] = height
					$scale = $size[0]/460;
					$check = $size[1]/$scale;
					if ($check > 400) 
						$tall = true;
				}
				?>
			<div id="imgCont" <?php if($tall) echo "class='right'";?>>
				<?php 
					if($tall)
						echo '<img id="articlePic" class="vertical" alt="'.get_img_title(get_img_id($article,1)).'" src="../inc/timthumb.php?src=../'.get_img_uri(get_img_id($article, 1)).'&w=240px&zc=1&a=t">';
					else 
						echo '<img id="articlePic" class="horizontal" alt="'.get_img_title(get_img_id($article,1)).'" src="../inc/timthumb.php?src=../'.get_img_uri(get_img_id($article, 1)).'&w=460px&zc=1&a=t">';
				?>
			</div>
			<?php 
				$caption = get_img_caption(get_img_id($article, 1));
				$attr = get_img_attr(get_img_id($article, 1));
				$attr_link = get_img_attr_link(get_img_id($article, 1));
				if ( $caption || $attr) { ?>
					<div id="imageCaption">
						<?php if ($caption) echo $caption; ?>
						<div id="imageAttr">
							<?php if($attr) echo ' - '; // TODO: sort this mess out! ?>
								<?php if($attr_link) echo '<a href="'.$attr_link.'">'?>
								<?php if ($attr) echo 'Credit: '.$attr; ?>
								<?php if($attr_link) echo '</a>'?>
						</div>
					</div>
				<?php } ?>
		<?php } } ?>
	
	<!-- Article content -->
	<div id="content">
		<p><?php echo clean_content2(get_article_text($article));?></p>
	</div>
	
	<!-- Comments (page break) -->

	<!-- Thank you message -->
</div>

<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.2.min.js"%3E%3C/script%3E'))</script>
  
  
<script>
	$(document).ready(function() {
		window.print();
	});
</script>


</body>
</html>