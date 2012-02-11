<?php
	/*
		TODO:
			Sort out image hiding/alignment mess
	*/

	global $gallerypage;
	$gallerypage = false;

	$noArticleE = false;
	$articleMissE = false;

	// IDEA: inline function calling instead of assinging sections to variables? Or use article class
	if ($_GET['article'] == '') {
		$noArticleE = true;
	} else {
		$article = $_GET['article'];
		$sql = "SELECT * FROM `article` WHERE id='$article' AND text1 IS NOT NULL";
		$result = mysql_query($sql);
		if (!$result){
			$articleMissE = true;
		} else {
			$row = mysql_fetch_array( $result );
			$title = $row['title']; // Define title
			$teaser = $row['teaser']; // Define teaser
			$authorID = $row['author']; //get_article_author_uname($article); // Define author ID
			$author = get_article_author_vname($article); // Define author
			$date = strtotime($row['published']);
			$content = shortcodes(clean_content2(get_article_text($article))); // Get content of article (text1)
			$category = get_article_category_cat($article);
			$category_display = get_article_category($article);
			$image = $row['img1'];
			if ($image != ''){
				$image_title = get_img_title(get_img_id($article,1));
			}
			$num_comments = get_article_comments($article);
			$article_URL = article_url($article);

			hit_article($article);
			log_page_visit($article);
		}
	}
?>

<script>
	var article = "<?php echo $article; ?>";
</script>

	<!-- Article wrapper -->
	<div class="container_12">

		<?php if($gallerypage) {
			// get photo gallery based on id
			$sql = "SELECT * FROM `media_photo_images` WHERE albumID='".$gallerypage."' ORDER BY id ASC";
			$result = mysql_query($sql);
		?>
		<div class="article gallery">
			<!-- Normal header -->
			<h2 class="grid_12"><?php echo $title; ?></h2>
			<div class="clear"></div>
			<div class="articleInfo grid_12">
				<p><?php echo output_in_english_authors(get_article_authors_uname($article)); ?></p>
				<p><span class="<?php echo $category;?>"><a href="<?php echo $category;?>/"><?php echo $category_display;?></a></span> - <?php echo date("l F j, Y", $date);?></p>
				<?php
					if (is_logged_in()) {
						$allowed = false;
						if(check_if_section_editor($uname, $article))  // if user is editor of section article is in
							$allowed = true;
						else if (get_user_role($uname)==100) // if super user
							$allowed = true;

						if ($allowed) { ?>
					<span id="editpage"><a href="/preview/engine/?page=addarticle&article=<?php echo $article;?>">Edit Page</a></span>
				<?php	}
					}
				?>
			</div>
			<div class="clear"></div>
			<div id="photogallery" class="grid_12">
				<ul>
		<?php while($row = mysql_fetch_array($result)){ ?>
				<img src="/gallery/gallery_images/timthumb.php?src=/gallery/gallery_images/images/<?php echo $row['imageName'];?>&h=510px&zc=0" title="<?php echo $row['imageTitle']; ?>" height="310" alt="<?php echo $row['imageCaption']; ?>"/>
		<?php } ?>
			</div>
		</div>
		<?php } ?>

		<!-- Sidebar -->
		<div class="sidebar grid_4 push_8">
			<?php
				// Initialise featured articles
				$pg = $category;
				$sql = "SELECT top_slider_1,top_slider_2,top_slider_3,top_sidebar_1,top_sidebar_2,top_sidebar_3,top_sidebar_4,top_sidebar_5 FROM `category` WHERE cat='$pg'";
				$top_articles = mysql_fetch_array(mysql_query($sql,$cid));
				list($b1,$b2,$b3,$c1,$c2,$c3,$c4,$c5) = $top_articles;
			?>
			<div id="featuredBox" <?php if($category == 'phoenix') echo 'class="featboxphoenix"';?>>
				<?php if($category == 'phoenix') { ?>
					<h3><?php echo $category_display;?></h3>
					<ul>
						<li><a href="phoenix/act1/">Act I</a></li>
						<li><a href="phoenix/act2/">Act II</a></li>
						<li><a href="phoenix/act3/">Act III</a></li>
					</ul>
				<?php } else { ?>
				<h3>Featured <span class="<?php echo $category;?>"><?php echo $category_display;?></span> Stories</h3>
				<ul>
					<?php
						for ($i=1;$i<5;$i++) {
							if ($i==1) {
					?>
								<li class="withPic">
									<a href="<?php echo article_url(${c.$i}); ?>">
										<h5><?php echo get_article_title(${c.$i});?></h5>
										<div class="featuredPic">
											<a href="<?php echo article_url(${c.$i}); ?>">
                                                <img id="featuredPhoto" alt="<?php echo get_img_title(get_img_id(${c.$i},1));?>" src="<?php echo get_img_url(get_img_id(${c.$i}, 1), 150, 100); ?>">
											</a>
										</div>
									</a>
									<div class="clear"></div>
								</li>
					<?php } else { ?>
							<li><a href="<?php echo article_url(${c.$i}); ?>"><?php echo get_article_title(${c.$i});?></a></li>
					<?php } } ?>
				</ul>
				<?php } ?>
			</div>
			<?php
                //include_once('sidebar/sexsurvey.php');
				include_once('sidebar/socialLinks.php');
				include_once('sidebar/mostPopular.php');
				include_once('sidebar/mediaBox.php');
				include_once('sidebar/fbActivity.php');
			?>
		</div>
		<!-- End of sidebar -->

		<div class="article grid_8 pull_4 alpha <?php echo $category;?> instapaper_body hentry">

		<?php
		if ($articleMissE) {
			echo "<h5 class='grid_8'>Opps! We couldn't find that article. Please return to the homepage and try again.</h5>";
		} else if ($noArticleE){
			echo "<h5 class='grid_8'>ERROR - no article specified</h5>";
		} else {
		?>
			<!-- Article header -->
			<!-- Comment header -->
			<?php if ($category == 'comment') { ?>
				<div class="grid_5">
					<h2 class="instapaper_title entry-title"><?php echo $title; ?></h2>
					<div class="subHeader"><?php echo $teaser; ?></div>
				</div>
				<div class="grid_3 alpha omega" id="commentArticlePic">
					<a href="user/<?php echo $authorID;?>/" title="<?php echo $author;?>"><img id="articlePic" alt="<?php echo $author;?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_user_pic($authorID));?>&h=160px&w=220px&zc=1"></a>
				</div>
			<?php } else { ?>
                <?php if($gallerypage) {
                    } else { ?>
                <!-- Normal header -->
                <h2 class="grid_8 instapaper_title entry-title"><?php echo $title; ?></h2>
                <?php if ($category != 'phoenix') { ?>
                <div class="subHeader grid_8"><?php echo $teaser; ?></div>
			<?php } } } ?>
			<?php if(!$gallerypage) { ?>
			<div class="articleInfo grid_8">
				<p><?php echo output_in_english_authors(get_article_authors_uname($article)); ?></p>
				<p><span class="<?php echo $category;?>"><a href="<?php echo $category;?>/"><?php echo $category_display;?></a></span> - <?php echo date("l F j, Y", $date);?></p>
				<?php
					if (is_logged_in()) {
						$allowed = false;
						if(check_if_section_editor($uname, $article))  // if user is editor of section article is in
							$allowed = true;
						else if (get_user_role($uname)==100) // if super user
							$allowed = true;

						if ($allowed) { ?>
					<span id="editpage"><a href="/engine/?page=addarticle&article=<?php echo $article;?>">Edit Page</a></span>
				<?php	}
					}
				?>
			</div>
			<!-- End of article header -->
			<?php } ?>
			<!-- Sidebar 2 -->
			<div class="sidebar2 grid_2 push_6 entry-unrelated">
				<div id="sharebuttonsCont">
					<h6>Sharing</h6>
					<ul>
						<div id="sharebuttons">
							<li id="facebookLike">

							</li>
							<li id="twitterShare">

							</li>
							<li id="googleShare">
								<g:plusone size="medium"></g:plusone>
							</li>
							<li id="diggShare">

							</li>
						</div>
					</ul>
				</div>
				<ul class="metaList">
					<li id="comments"><a href="<?php echo curPageURLNonSecure().'#commentHeader';?>"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li><a href="javascript:;" onclick="document.location.hash='anchor';">
					<!--<li><a href="<?php echo curPageURLNonSecure();?>#emailArticle" rel="facebox">Email Article</a></li>-->
					<li><a href="print.php?article=<?php echo $article;?>" target="_blank">Print Article</a></li> <!-- TODO -->
				</ul>
			</div>
			<!-- End of Sidebar 2 -->

			<!-- Content -->
			<div class="content grid_6 pull_2 omega entry-content">
				<?php
				if ($category != 'comment') {
					if ($image != ''){
						if ($image == 183 || $image == 742) {
						} else {?>
						<?php if ($category == 'phoenix') { ?>
							<div id="imgCont" >
								<?php
										echo '<img id="articlePic" class="horizontal" alt="'.$image_title.'" src="'.get_img_url(get_img_id($article, 1), 460).'">';
								?>
								<?php
								$caption = get_img_caption(get_img_id($article, 1));
								$attr = get_img_attr(get_img_id($article, 1));
								$attr_link = get_img_attr_link(get_img_id($article, 1));
								if ( $caption || $attr) { ?>
								<div id="imageCaption">
									<?php if ($caption) echo $caption; ?>
									<div id="imageAttr">
										<?php if($attr && $caption) echo ' - '; // TODO: sort this mess out! ?>
										<?php if($attr_link) echo '<a href="'.$attr_link.'">'?>
											<?php if ($attr) echo 'Credit: '.$attr; ?>
										<?php if($attr_link) echo '</a>'?>
									</div>
								</div>
								<?php } ?>
							</div>

						<?php } else { ?>
						<?php
								$size = getimagesize(get_img_url(get_img_id($article, 1))); // $size[0] = width, $size[1] = height
								$scale = $size[0]/460;
								$check = $size[1]/$scale;
								if ($check > 400)
									$tall = true;
						?>
						<div id="imgCont" <?php if($tall) echo "class='right'";?>>
							<?php
								if($tall)
									echo '<img id="articlePic" class="vertical" alt="'.$image_title.'" src="'.get_img_url(get_img_id($article, 1), 240).'">';
								else
									echo '<img id="articlePic" class="horizontal" alt="'.$image_title.'" src="'.get_img_url(get_img_id($article, 1), 460).'">';
							?>
							<?php
							$caption = get_img_caption(get_img_id($article, 1));
							$attr = get_img_attr(get_img_id($article, 1));
							$attr_link = get_img_attr_link(get_img_id($article, 1));
							if ( $caption || $attr) { ?>
							<div id="imageCaption">
								<?php if ($caption) echo $caption; ?>
								<div id="imageAttr">
									<?php if($attr && $caption) echo ' - '; // TODO: sort this mess out! ?>
									<?php if($attr_link) echo '<a href="'.$attr_link.'">'?>
										<?php if ($attr) echo 'Credit: '.$attr; ?>
									<?php if($attr_link) echo '</a>'?>
								</div>
							</div>
							<?php } ?>
						</div>
				<?php } } }
				} else
					if ($image != ''){
					if ($image != get_user_pic($authorID)) { // if article image is not the author's image
						if ($image == 183 || $image == 742) { // if default images do nothing
						} else { ?>

						<?php
								$size = getimagesize(get_img_uri(get_img_id($article, 1))); // $size[0] = width, $size[1] = height
								$scale = $size[0]/460;
								$check = $size[1]/$scale;
								if ($check > 400)
									$tall = true;
						?>
							<div id="imgCont" <?php if($tall) echo "class='right'";?>>
								<?php
									if($tall)
										//echo '<img id="articlePic" class="vertical" alt="'.$image_title.'" src="../inc/timthumb.php?src=../'.get_img_uri(get_img_id($article, 1)).'&h=400px&zc=1&a=t">';
										echo '<img id="articlePic" class="vertical" alt="'.$image_title.'" src="../inc/timthumb.php?src=../'.get_img_uri(get_img_id($article, 1)).'&w=240px&zc=1&a=t">';
									else
										echo '<img id="articlePic" class="horizontal" alt="'.$image_title.'" src="../inc/timthumb.php?src=../'.get_img_uri(get_img_id($article, 1)).'&w=460px&zc=1&a=t">';
								?>
								<?php
								$caption = get_img_caption(get_img_id($article, 1));
								$attr = get_img_attr(get_img_id($article, 1));
								$attr_link = get_img_attr_link(get_img_id($article, 1));
								if ( $caption || $attr) { ?>
								<div id="imageCaption">
									<?php if ($caption) echo $caption; ?>
									<div id="imageAttr">
										<?php if($attr && $caption) echo ' - '; // TODO: sort this mess out! ?>
										<?php if($attr_link) echo '<a href="'.$attr_link.'">'?>
											<?php if ($attr) echo 'Credit: '.$attr; ?>
										<?php if($attr_link) echo '</a>'?>
									</div>
								</div>
								<?php } ?>
							</div>
				<?php  }
					}
					}
				?>

				<?php echo $content; ?>
			</div>
			<!-- End of content -->

			<div class="articleShare grid_8">
				<ul>
                    <li>
                        <div id="shareText">Share: </div>
                    </li>
					<li>
						<div id="twitterShare2">
							<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="feliximperial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
						</div>
					</li>
					<li>
						<div id="diggShare2">
							<!--<a class="DiggThisButton DiggCompact"></a>-->
							<g:plusone size="medium"></g:plusone>
						</div>
					</li>
					<li>
						<div id="facebookLike2">
                        <fb:like send="true" width="300" show_faces="false" font="arial"></fb:like>
						</div>
					</li>
				</ul>
			</div>
			<div class="clear"></div>

			<!-- Comments -->
            <?php include('views/comments/commentCont.php'); ?>
			<!-- End of comments -->

			<div class="clear"></div>
		</div>
		<!-- End of article content -->
		<?php } ?>
		<div class="clear"></div>

	</div>
	<!-- End of article wrapper -->

	<!-- Email article div -->
	<div id="emailArticle">
		<form action="#" id="emailArticleForm" method="post">
			<h3>Email article</h3>
			<h6><?php echo get_article_title($article);?></h6>

			<label for="email">To:</label>
			<input id="email" name="email"/>
			<div id="emailInfo">(Seperate multiple address with a comma)</div>


			<label for="name">Your name:</label>
			<input id="name" name="name" <?php if(is_logged_in()) echo 'value="'.get_vname_by_uname_db($uname).'"';?>/>
			<div class="clear"></div>

			<label for="emailSender">Your email:</label>
			<input id="emailSender" name="emailSender" <?php if(is_logged_in()) echo 'value="'.get_user_email($uname).'"';?>/>
			<div class="clear"></div>

			<label for="note" id="noteLabel">Add note: <span>(optional)</span></label>
			<textarea name="note" id="note"></textarea>
			<div class="clear"></div>

			<input type="submit" id="submit" value="Send Email" />
		</form>
	</div>
