<?php
	$category = $_GET['cat'];
	if (!isset($_GET['p'])){
		$p=1;
	} else {
		$p = $_GET['p'];
	}
	$sql = "SELECT COUNT(*) FROM `article` AS a INNER JOIN `category` AS c ON (a.category=c.id) WHERE published < NOW() AND c.cat='$category' ORDER BY published DESC";
	$rows = mysql_result(mysql_query($sql,$cid),0,0);
	$sql = "SELECT a.id FROM `article` AS a INNER JOIN `category` AS c ON (a.category=c.id) WHERE published < NOW() AND c.cat='$category' ORDER BY published DESC LIMIT ".(($p-1)*ARTICLES_PER_CAT_PAGE).",".ARTICLES_PER_CAT_PAGE;
	$rsc = mysql_query($sql);
?>

	<!-- Section header -->
	<div class="container_12">
		<?php if (mysql_num_rows($rsc)) { ?>
		<div class="grid_12 section_header <?php echo $category; ?>">
			<h2><?php echo get_category_label_by_cat($category); ?></h2>
			<div id="info">
				<ul>
				<?php
				$editors = get_category_editors_by_cat(get_category_id_by_cat($category));
				if ($editors) {
					foreach($editors as  $i => $uname) {
						$editorfull[$i] = get_vname_by_uname_db($uname);
					}
					?>
					<li class="editors">Editors: <b><?php echo output_in_english($editorfull)?></b></li>
				<?php }
				$email = get_category_email_by_cat($category);
				if ($email) { ?>
					<li class="email"><?php echo hide_email($email);?></li>
				<?php } ?>
					<li class="rss"><a href="rss.php?cat=<?php echo $category;?>" target="_blank">RSS Feed</a></li>
				</ul>
			</div>
		</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<!-- End of section header -->

	<!-- Section articles -->
	<div class="container_12 section">

	<?php if (mysql_num_rows($rsc)) { ?>
		<!-- Sidebar -->
		<div class="sidebar grid_4 push_8">
			<?php
				if($sectwitter = get_section_twitter($category)) {
			?>
			<div class="twitterbox" id="<?php echo $sectwitter;?>">
				<h4>Twitter</h4>
				<div class="clear"></div>
				<div id="twitheader">
					<a href="http://twitter.com/" title="" id="twitpiclink"><img src="" width="50px" id="felixTwitterlogo"/></a>
					<h5></h5>
					<p><a href="http://twitter.com/" target="_blank" title=""></a> - <span></span></p>
					<div class="clear"></div>
				</div>
				<ul id="felixtwitterlist">
					<li>Loading....</li>
				</ul>
			</div>
			<?php } ?>

			<?php
			if (mysql_num_rows($rsc)) {
				// Initialise featured articles
				$pg = $category;
				$sql = "SELECT top_slider_1,top_slider_2,top_slider_3,top_slider_4,top_sidebar_1,top_sidebar_2,top_sidebar_3,top_sidebar_4,top_sidebar_5 FROM `category` WHERE cat='$pg'";
				$top_articles = mysql_fetch_array(mysql_query($sql,$cid));
				list($b1,$b2,$b3,$b4,$c1,$c2,$c3,$c4,$c5) = $top_articles;
			?>
			<div id="featuredBox">
				<h3>Top Stories</h3>
				<ul>
					<?php
						for ($i=1;$i<5;$i++) {
							if ($i==1) {
					?>
								<li class="withPic">
									<a href="<?php echo article_url(${b.$i}); ?>">
										<h5><?php echo get_article_title(${b.$i});?></h5>
										<div class="featuredPic">
											<a href="<?php echo article_url(${b.$i}); ?>">
												<img id="featuredPhoto" alt="<?php echo get_img_title(get_img_id(${b.$i},1));?>" src="<?php echo get_img_url(get_img_id(${b.$i}, 1), 150, 100);?>">
											</a>
										</div>
									</a>
									<div class="clear"></div>
								</li>
					<?php } else { ?>
							<li><a href="<?php echo article_url(${b.$i}); ?>"><?php echo get_article_title(${b.$i});?></a></li>
					<?php } } ?>
				</ul>
			</div>
			<?php }
            include_once('sidebar/sexsurvey.php');
			include_once('sidebar/mediaBox.php');
			include_once('sidebar/socialLinks.php');
			include_once('sidebar/fbActivity.php');
			include_once('sidebar/mostPopular.php');
			?>
		</div>
		<!-- End of sidebar -->
	<?php } ?>

	<?php
		if (mysql_num_rows($rsc)) {?>
		<div class="grid_8 pull_4">
	<?php	if ($p==1) {
			$i = 0;
			while (list($article) = mysql_fetch_array($rsc)) {
				$i++;
				if ($i == 1) {
	?>
		<!-- Top story -->
		<div class="topstory">
			<?php
				$tall = false;
				$image = get_img_id($article,1);
				if($image) {
					$size = getimagesize(get_img_uri($image)); // $size[0] = width, $size[1] = height
					$scale = $size[0]/300;
					$check = $size[1]/$scale;
					if ($check > 300)
						$tall = true;
				}
			?>
			<div class="border">
			<h2><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h2>
			<div class="subHeader <?php if($image == '' || $image == 183 || $image == 742) echo "wide"; if($tall) echo ' tallpic';?>">
				<p><?php if($image == '' || $image == 183 || $image == 742) echo get_article_preview_trunc($article, 50); else echo get_article_preview_trunc($article, 35);?></p>
				<div id="storyMeta">
					<ul class="metaList">
						<?php if ($category == 'comment') { ?>
							<li id="articleAuthor"><a href="user/<?php echo get_article_author_uname($article);?>/"><?php echo get_article_author_vname($article); ?></a></li>
						<?php } ?>
						<?php if($num_comments = get_article_comments($article)) { ?>
							<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
						<?php } ?>
						<li><?php echo date("l F j, Y",get_article_date($article));?></li>
					</ul>
				</div>
			</div>
			<?php
			if ($image != ''){
				if ($image == 183 || $image == 742) {
				} else {?>
				<div id="topStoryPic">
					<a href="<?php echo article_url($article);?>">
					<?php if($tall) { ?>
						<img id="topStoryPhoto" alt="<?php echo get_img_title($image);?>" src="<?php echo get_img_url($image, 150, 180);?>">
					<?php } else { ?>
						<img id="topStoryPhoto" alt="<?php echo get_img_title($image);?>" src="<?php echo get_img_url($image, 300, 180);?>">
					<?php } ?>
					</a>
				</div>
			<?php } } ?>
			<div class="clear"></div>
			</div>
		</div>
		<!-- End of top story -->
	<?php
				} if ($i < 4 && $i > 1) {
	?>
		<div class="featBox">
			<?php
				$tall = false;
				$image = get_img_id($article,1);
				if($image) {
					$size = getimagesize(get_img_uri($image)); // $size[0] = width, $size[1] = height
					$scale = $size[0]/220;
					$check = $size[1]/$scale;
					if ($check > 220)
						$tall = true;
				}
			?>
			<div class="border">
				<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
				<div class="subHeader <?php if($image == '' || $image == 183 || $image == 742) echo "wide"; if($tall) echo ' tallpic';?>">
					<p><?php if($image == '' || $image == 183 || $image == 742) echo get_article_preview_trunc($article, 60); else echo get_article_preview_trunc($article, 35); ?></p>
					<div id="storyMeta" class="<?php if(!$num_comments = get_article_comments($article)) echo 'extra';?>">
						<ul class="metaList">
							<?php if ($category == 'comment') { ?>
								<li id="articleAuthor"><a href="user/<?php echo get_article_author_uname($article);?>/"><?php echo get_article_author_vname($article); ?></a></li>
							<?php } ?>
							<?php if($num_comments) { ?>
								<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
							<?php } ?>
							<li><?php echo date("l F j, Y",get_article_date($article));?></li>
						</ul>
					</div>
				</div>
				<?php
				if ($image != ''){
					if ($image == 183 || $image == 742) {
					} else {?>
					<div id="secondStoryPic">
						<a href="<?php echo article_url($article);?>">
						<?php if($tall) { ?>
							<img id="secondStoryPhoto" alt="<?php echo get_img_title($image_title);?>" src="<?php echo get_img_url($image, 120, 130);?>">
						<?php } else { ?>
							<img id="secondStoryPhoto" alt="<?php echo get_img_title($image_title);?>" src="<?php echo get_img_url($image, 220, 130);?>">
						<?php } ?>
						</a>
					</div>
				<?php } } ?>
				<div class="clear"></div>
			</div>
		</div>

	<?php 		} if ($i > 3) { ?>

		<div class="featBox">
			<?php
				$tall = false;
				$image = get_img_id($article,1);
				if($image) {
					$size = getimagesize(get_img_uri($image)); // $size[0] = width, $size[1] = height
					$scale = $size[0]/160;
					$check = $size[1]/$scale;
					if ($check > 160)
						$tall = true;
				}
			?>
			<div class="border">
				<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
				<div class="subHeader third <?php if($image == '' || $image == 183 || $image == 742) echo "wide"; if($tall) echo ' tallpic';?>">
					<p><?php if($image == '' || $image == 183 || $image == 742) echo get_article_preview_trunc($article, 60); else echo get_article_preview_trunc($article, 30); ?></p>
					<div id="storyMeta">
						<ul class="metaList">
							<?php if ($category == 'comment') { ?>
								<li id="articleAuthor"><a href="user/<?php echo get_article_author_uname($article);?>/"><?php echo get_article_author_vname($article); ?></a></li>
							<?php } ?>
							<?php if($num_comments = get_article_comments($article)) { ?>
								<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
							<?php } ?>
							<li><?php echo date("l F j, Y",get_article_date($article));?></li>
						</ul>
					</div>
				</div>
				<?php
				if ($image != ''){
					if ($image == 183 || $image == 742) {
					} else {?>
					<div id="thirdStoryPic">
						<a href="<?php echo article_url($article);?>">
							<?php if($tall) { ?>
								<img id="topStoryPhoto" alt="<?php echo get_img_title($image);?>" src="<?php echo get_img_url($image, 100, 120);?>">
							<?php } else { ?>
								<img id="topStoryPhoto" alt="<?php echo get_img_title($image);?>" src="<?php echo get_img_url($image, 160, 120);?>">
							<?php } ?>
						</a>
					</div>
				<?php } } ?>
				<div class="clear"></div>
			</div>
		</div>

	<?php 		} // End of if
				} // End of while

				} else { // If page is not first
					$sql = "SELECT a.id FROM `article` AS a INNER JOIN `category` AS c ON (a.category=c.id) WHERE published < NOW() AND c.cat='$category' ORDER BY published DESC LIMIT ".(($p-1)*ARTICLES_PER_SECOND_CAT_PAGE).",".ARTICLES_PER_SECOND_CAT_PAGE;
					$rsc2 = mysql_query($sql);
					while (list($article) = mysql_fetch_array($rsc2)) { ?>
						<div class="featBox">
							<div class="border">
								<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
								<p><?php echo get_article_preview_trunc($article, 30); ?></p>
								<div id="storyMeta">
									<ul class="metaList">
										<?php if ($category == 'comment') { ?>
											<li id="articleAuthor"><a href="user/<?php echo get_article_author_uname($article);?>/"><?php echo get_article_author_vname($article); ?></a></li>
										<?php } ?>
										<?php if($num_comments = get_article_comments($article)) { ?>
											<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
										<?php } ?>
										<li><?php echo date("l F j, Y",get_article_date($article));?></li>
									</ul>
								</div>
								<div class="clear"></div>
							</div>
						</div>
	<?php			} // End of while
				} // End of if

				$num_articles = mysql_num_rows($rsc); ?>
				<!-- Page list -->
				<div class="featBox>">
					<ul id="pageList">
						<li id="desc">Pages:</li>
						<?php if ($p != 1) // Previous page arrow
								echo '<li class="arrow"><a href="'.$category.'/'.($p-1).'/">&#171;</a></li>';

							$pages = ceil(($rows-ARTICLES_PER_CAT_PAGE)/ARTICLES_PER_SECOND_CAT_PAGE)+1;
							if ($pages>1) {
								$span = NUMBER_OF_PAGES_IN_PAGE_LIST;
								if ($pages > $span) {
									if ($p >= ($span/2)) {
										$start = ($p - $span/2)+1;
										$limit = $p + $span/2;
										if ($limit > $pages) {
											$limit = $pages;
											$start = $limit - $span;
										}
									} else {
										$start = 1;
										$limit = $span;
									}
								} else {
									$limit = $pages;
									$start = 1;
								}
								for ($i=$start;$i<=$limit;$i++)
									echo (($p==$i)?'<li class="selected">':('<li><a href="'.$category.'/'.$i.'/">')).$i.(($p==$i)?'</li>':'</a></li>');
							} else {
								echo '<li class="selected">1</li>';
							}
							if ($p != $pages) // Next page arrow
								echo '<li class="arrow"><a href="'.$category.'/'.($p+1).'/">&#187;</a></li>';
						?>
					</ul>
				</div>
				<div class="clear"></div>
	<?php } else { ?>
		<?php include('404cont.php'); ?>
	<?php	}
	?>
	</div>
