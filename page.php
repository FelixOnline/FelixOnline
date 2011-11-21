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
					<li><div id="shareText">Share: </div></li>
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
							<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
							<fb:like show_faces="false" width="300" font="arial" send=true></fb:like>
						</div>
					</li>
				</ul>
			</div>
			<div class="clear"></div>

			<!-- Comments -->
			<?php
                // TODO
				$sql = "SELECT * FROM (".
					" SELECT comment.id,comment.user,name,comment,UNIX_TIMESTAMP(comment.timestamp) AS timestamp FROM `comment` LEFT JOIN `user` ON (comment.user=user.user) WHERE article=$article AND active=1".
					" UNION SELECT comment_ext.id,'extuser0',comment_ext.name,comment_ext.comment,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP != '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=0".
					" UNION SELECT comment_ext.id,'extuser1',comment_ext.name,comment_ext.comment,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP = '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=1".
					" UNION SELECT comment_ext.id,'extuser2',comment_ext.name,comment_ext.comment,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP = '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=0".
					") AS t ORDER BY timestamp ASC LIMIT 500";
				if (!$result = mysql_query($sql,$cid))
					echo mysql_error();
			?>
			<div class="grid_8 comments" id="commentHeader">
				<h3>Comments <span>(<?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?>)</span></h3>
				<a href="<?php echo curPageURLNonSecure().'#commentForm';?>" id="postComment">Post a comment</a>

				<!-- Comment container -->
				<div id="commentCont">
				<?php
					while ($row = mysql_fetch_array($result)) {
				?>
					<div class="singleComment" id="comment<?php echo $row['id'];?>">
						<div class="comment">
							<div class="commentInfo">
								<p id="commentUser">
								<?php
									if ($commenter = $row['name']) {  // Check if commenter has a name
										if ($row['user'] == 'extuser0' || $row['user'] == 'extuser1' || $row['user'] == 'extuser2') { // If commenter has name but is not registered then just output commenter name
											echo $commenter;
										} else { // If commenter has name and is registered then provide link to user?>
											<a href="user/<?php echo $row['user'];?>/"><?php echo $commenter; ?></a>
											<?php if(in_array($row['user'], get_article_authors_uname($article))) echo '<span>(Author)</span>'; ?>
								<?php 	}
									} else { // If commenter has no name then just state anonymous
										echo 'Anonymous';
									} ?>
								</p>
								<span id="commentDate"><?php echo date('l F d Y H:i',$row['timestamp']); //22 September 2010 14:28:49?></span>
							</div>
							<p>
								<?php if($reply = comment_is_reply($row['id'], $row['user'])) { ?><a href="<?php echo curPageURLNonSecure().'#comment'.$reply; ?>" id="replyLink">@<?php echo get_comment_author($reply, $row['user']);?></a>: <?php } ?>
								<?php echo html_entity_decode(nl2br($row['comment'])); ?>
							</p>
						</div>
						<div class="commentAction" id="<?php echo $row['id'];?>">
							<ul>
								<li><?php
									if (!is_logged_in()) {?>
										<a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox" class="likeComment">Like</a>
									<?php } else {
										if (user_like_comment($row['id'], is_logged_in())) echo 'Liked'; // if user has already liked or disliked comment then remove link
										else {?>
											<a href="<?php echo curPageURLNonSecure();?>#" id="like">Like</a>
									<?php } } ?>
									<span id="likecounter">(<?php echo get_likes($row['id']);?>)</span>
								</li>
									<li><?php if (!is_logged_in()) { ?>
										<a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox" class="dislikeComment">Dislike</a>
									<?php } else {
										if (user_like_comment($row['id'], is_logged_in())) echo 'Disliked';
										else {?>
										<a href="<?php echo curPageURLNonSecure();?>#" id="dislike">Dislike</a>
									<?php } }?>
									<span id="dislikecounter">(<?php echo get_dislikes($row['id']);?>)</span>
								</li>
								<li><a href="<?php echo curPageURLNonSecure().'#comment'.$row['id'];?>" id="commentLink">Link</a></li>
								<li class="last"><a href="<?php echo curPageURLNonSecure().'#comment'.$row['id']; ?>" id="<?php echo $row['id'];?>" class="replyToComment">Reply to</a></li>
							</ul>
						</div>
						<div class="clear"></div>
					</div>

					<?php } ?>
				</div>

				<!-- Comment form -->
				<div id="commentForm">

					<script type="text/javascript">
						var RecaptchaOptions = {
							theme : 'clean'
						};
					</script>
					<?php

					// Error diplaying
					if ($errorinsert)
						echo '<div class="commenterror">System error - please email <a href="mailto"felix@imperial.ac.uk@>felix@imperial.ac.uk</a>!</div>';
					if ($errorduplicate)
						echo '<div class="commenterror">Duplicate comment submitted.</div>';

					if (!$uname) { ?>
						<h5>Comment anonymously or <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox">Log in</a></h5>
					<?php } else { ?>
						<h5>Leave a comment as <a href="user/<?php echo $uname;?>/" title="Profile Page"><?php echo get_vname();?></a></h5>
					<?php } ?>
					<form method="post" action="<?php echo curPageURLNonSecure();?>">
						<?php if (!$uname) { ?>
							<label for="name">Name: </label><input name="name" id="name"/>
							<div class="clear"></div>
						<?php } else { ?>
							<input type="hidden" value="<?php echo $uname; ?>"/>
						<?php } ?>
						<div id="comentbox">
							<label for="comment" id="commentLabel">Comment: </label>
							<div class="clear"></div>
							<textarea name="comment" id="comment" rows="4" class="required"></textarea>
							<label for="comment" class="error">Please write a comment</label>
						</div>
						<div class="clear"></div>
						<?php if (!$uname) { ?>
							<label for="capatca">To prove you are human: </label>
							<div class="clear"></div>
							<?php
							require_once('inc/recaptchalib.php');
							$publickey = "6LdbYL4SAAAAAKufkLBCRiEmbTRawSFaWDDJwQwB";
							//$privatekey = "6LdbYL4SAAAAAOAUmQ4QSXUbSYm1LIkgbvqZBWXU";
							echo recaptcha_get_html($publickey);
							//A.This div notifies the user whether the Recaptcha was Successful or not
							echo '<label for="recaptcha_response_field" class="error" id="captchaStatus"></label>';
							?>
						<?php } ?>
						<input type="submit" value="Post your comment" id="submit" name="<?php if($uname) echo 'articlecomment'; else echo 'articlecomment_ext';?>"/>
					</form>
				</div>
			</div>
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
