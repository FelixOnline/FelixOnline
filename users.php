<?php 
	/*
		TODO:
			Add images to all front page images
			Add hover text underline for the read more link
	*/
	
	$error = false;
	
	// Initialise user
	$user = $_GET['id'];
	if(!check_user($_GET['id'])) {
		$error = true;
	} else {
		$name = get_vname_by_uname_db($user); // Get name
	}
	if (!isset($_GET['p'])){ 
		$p=1;
	} else {
		$p = $_GET['p'];
	}
						
	$sql = "SELECT article.id, article.hidden, article.published, article.title, article.img1 FROM `article` INNER JOIN `article_author` ON (article.id=article_author.article) WHERE article_author.author='$user' AND `published` ORDER BY article.date DESC";
	$result = mysql_query($sql);
	$articles = mysql_num_rows($result);
	
	//$articles = count_articles_by_author_all($user); // Get articles from user
	$comments = count_comments_by_author($user); // Get comments from user
	
	$info = get_user_info_by_uname_ldap($user); // Get user info
	$info = explode('|',$info);
?>
	
	<!-- Article wrapper -->
	<div class="container_12 usercontainer">
	<?php if(!$error) {?>
		<!-- Sidebar -->
		<div class="sidebar grid_4 push_8">
			<?php if ($articles > 2) { ?>
				<div id="userPopular">
					<h3>Most Popular Articles</h3>
					<ol>
					<?php foreach(get_articles_by_user_popular($user) as $article) { ?>
						<li id="userPopList">
							<div id="popTitle">
								<?php if (is_logged_in() == $user) { ?>
								<div id="popHits">
									<?php echo get_article_hits($article); ?> hits
								</div>
								<?php } ?>
								<a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a>
							</div>
						</li>
					<?php } ?>
					</ol>
				</div>
			<?php } ?>
			<?php if ($comments) { ?>
				<div id="recentComments">
					<h3>Recent Comments</h3>
					<?php 
					if ($user_comment_popularity = get_user_comment_popularity($user)) {
						list($dislikes,$likes) = $user_comment_popularity;
						$ratings = $dislikes + $likes;
						$popularity = round(100 * $likes / $ratings);
					}
					if ($ratings) { ?>
						<span id="popularity">(Popularity: <?php echo $popularity;?>% over <?php echo $ratings;?> ratings)</span>
					<?php } ?>
					<ul id="commentList">
						<?php foreach (get_article_comments_by_user($user) as $comment) {
							echo '<li><a href="'.article_url($comment[0]).'">'.get_article_title($comment[0]).'</a> <p>"'.trim_text($comment[2], 130).'"</p></li>';
						} ?>
					</ul>
					
					<?php // if number of comments is greater than NUMBER_OF_POPULAR_COMMENTS_USER then add link to view all comments ?>
				</div>
			<?php } ?>
			<?php 
				include_once('sidebar/fbActivity.php');
				include_once('sidebar/mostPopular.php');
				include_once('sidebar/mediaBox.php');
			?>
		</div>
		<!-- End of sidebar -->
		
		<div class="grid_8 pull_4 user omega">
			<!--<div id="userPic">
				<img src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_user_pic($user));?>&h=130px&w=130px&zc=1&a=t"/>
				<div class="clear"></div>
			</div>-->
			<div id="userInfoCont">
			<form id="profileform">
				<h2><?php echo $name; ?><span><?php if (is_logged_in() == $user) { ?><a href="#" id="editProfile">Edit Profile</a><a href="#" id="editProfileSave" style="display:none;">Save Profile</a><?php } ?></span><span class="loading">Saving...</span></h2>
				<ul id="userInfo">
					<li><?php echo $info[0]; ?></li>
					<li><?php echo $info[1]; ?></li>
				</ul>
				
				<?php 
				if ($articles || $comments) {
				?>
					<p><?php if ($articles){ echo 'Author of '.$articles; ?> article<?php echo ($articles != 1 ? 's' : '');?><?php } ?>
					<?php if($comments && $articles){?> and <?php } ?>  
					<?php if($comments) {echo $comments;?> comment<?php echo ($comments != 1 ? 's' : ''); }?> 
					since <?php echo date('d/m/Y',get_firstdate($user));?></p>
					<p>Last login: <?php echo date('d/m/Y',get_lastdate($user)); ?></p>
				<?php } ?>
			</div>
			<div class="clear"></div>
			<?php 
					$description = get_user_description($user);
					$facebook = get_user_facebook($user);
					$twitter = get_user_twitter($user);
					$email = get_user_email($user);
					$website = get_user_website($user);
					$websitename = get_user_website_name($user);
					
				//if($description || $facebook || $twitter || $email || $website) {
			?>	
			<div id="personalCont">
					<div id="descCont">
						<?php if ($description) echo $description;
								else if (is_logged_in() == $user) echo "Add some personal info....";?>
					</div>
					<div id="personalLinks">
						<ul>
							<li class="facebook" <?=($facebook)?'':'style="display:none;"'?>><a href="<?php echo $facebook; ?>" target="_blank">Facebook</a></li>
							<li class="twitter" <?=($twitter)?'':'style="display:none;"'?>><a href="http://www.twitter.com/<?php echo $twitter; ?>" target="_blank">@<?php echo $twitter; ?></a></li>
							<li class="useremail" <?=($email)?'':'style="display:none;"'?>><?=($email) ? hide_email($email) : '<a></a>' ?></li>
							<li class="website" <?=($website)?'':'style="display:none;"'?>><a href="<?=$website?>" target="_blank"><?=$websitename?></a></li>
						</ul>
					</div>
				<div class="clear"></div>
			</div>
			<?php //} ?>
			<?php if(is_logged_in() == $user) { ?>
			<div id="personalCont" class="edit" style="display: none;">
				<div id="descCont">
					<textarea placeholder="Add some personal info..."><?=$description?></textarea>
				</div>
				<div id="personalLinksEdit">
					<ul>
						<li class="facebook"><input type="text" class="url" value="<?=$facebook?>" placeholder="http://www.facebook.com/joe.bloggs"/></li>
						<li class="twitter">@<input type="text" value="<?=$twitter?>" placeholder="twitter"/></li>
						<li class="useremail"><input type="text" class="required email" value="<?=($email ? $email : '')?>" placeholder="name@domain.com"/></li>
						<li class="website"><input type="text" id="name" value="<?=$websitename?>" placeholder="Name"/><input type="text" id="url" class="url" value="<?=$website?>" placeholder="Url"/></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<?php } ?>
			</form>
				<?php if ($articles) { ?>
					<div id="articleListCont">
						<h3 id="userArticleTitle">Articles <span><a href="rss.php?id=<?php echo $user;?>" target="_blank" id="userRSS">RSS Feed</a></span></h3>
						<?php 
						
						$sql = "SELECT article.id, article.hidden, article.published, article.title, article.img1 FROM `article` INNER JOIN `article_author` ON (article.id=article_author.article) WHERE article_author.author='$user' AND `published` ORDER BY article.date DESC LIMIT ".(($p-1)*ARTICLES_PER_USER_PAGE).",".ARTICLES_PER_USER_PAGE;
									
						//$sql = "SELECT id FROM `article` WHERE author='$user' AND `published` IS NOT NULL ORDER BY date DESC LIMIT ".(($p-1)*ARTICLES_PER_USER_PAGE).",".ARTICLES_PER_USER_PAGE;
						$rsc = mysql_query($sql);
							
						if ($p==1) {
							while (list($article) = mysql_fetch_array($rsc)) {
							//foreach(get_articles_by_user($user) as $article) { 
								$i++;
								if ($i < 4) {
						?>
							<div class="userArticle">
								<div class="userArticleDate grid_1 alpha">
									<span><?php echo date('jS',get_article_date($article)); ?></span><br/>
									<?php echo date('F Y',get_article_date($article)); ?><br/>
									<?php if (is_logged_in() == $user) { ?>
										<div><?php echo get_article_hits($article); ?> hits</div>
									<?php } ?>
								</div>
								<?php 
									$imageid = get_img_id($article, 1);
									$image = get_img_uri($imageid);
									if($imageid) {
										$tall = false;
										$size = getimagesize($image); // $size[0] = width, $size[1] = height
										$scale = $size[0]/220;
										$check = $size[1]/$scale;
										if ($check > 200) 
											$tall = true;
									}
								?>
								<div class="userArticleInfo grid_7 omega <?php if (get_article_category_cat($article) == 'comment' || $imageid == 183 || $imageid == 742) echo 'second';?>">
									<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
									<div class="subHeader <?php if($tall) echo 'wide';?>" >
										<p><?php echo get_article_preview_trunc($article, 30); ?></p>
										<div id="storyMeta">
											<ul class="metaList">
												<li id="category"><a href="<?php echo get_article_category_cat($article);?>" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
												<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php $num_comments = get_article_comments($article); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
											</ul>
										</div>
									</div>
									<?php if (get_article_category_cat($article) != 'comment') { 
										if($imageid) {
										if($imageid == 183 || $imageid == 742) {
										} else { ?>
									<div id="secondStoryPic">
										<a href="<?php echo article_url($article);?>">
											<?php if($tall) { ?>
												<img id="secondStoryPhoto" alt="<?php echo get_img_title(get_img_id($article, 1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($article, 1));?>&h=155px&w=120px&zc=1&a=t">
											<?php } else { ?>
												<img id="secondStoryPhoto" alt="<?php echo get_img_title(get_img_id($article, 1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($article, 1));?>&h=150px&w=220px&zc=1&a=t">
											<?php } ?>
										</a>
									</div>
									<?php } } } ?>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>
						<?php } else { ?>
							<div class="userArticle">
								<div class="userArticleDate grid_1 alpha">
									<span><?php echo date('jS',get_article_date($article)); ?></span><br/>
									<?php echo date('F Y',get_article_date($article)); ?>
									<?php if (is_logged_in() == $user) { ?>
										<div><?php echo get_article_hits($article); ?> hits</div>
									<?php } ?>
								</div>
								<div class="userArticleInfo grid_7 omega second">
									<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
									<div class="subHeader">
										<p><?php echo get_article_preview_trunc($article, 30); ?></p>
										<div id="storyMeta">
											<ul class="metaList">
												<li id="category"><a href="<?php echo get_article_category_cat($article);?>" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
												<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php $num_comments = get_article_comments($article); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
											</ul>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>	
						<?php } } ?>
						<?php } else { 
							while (list($article) = mysql_fetch_array($rsc)) { ?>
							<div class="userArticle">
								<div class="userArticleDate grid_1 alpha">
									<span><?php echo date('jS',get_article_date($article)); ?></span><br/>
									<?php echo date('F Y',get_article_date($article)); ?>
									<?php if (is_logged_in() == $user) { ?>
										<div><?php echo get_article_hits($article); ?> hits</div>
									<?php } ?>
								</div>
								<div class="userArticleInfo grid_7 omega second">
									<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
									<div class="subHeader">
										<p><?php echo get_article_preview_trunc($article, 30); ?></p>
										<div id="storyMeta">
											<ul class="metaList">
												<li id="category"><a href="<?php echo get_article_category_cat($article);?>" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
												<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php $num_comments = get_article_comments($article); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
											</ul>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>	
						
						<?php } } ?>
					</div>
					
					<?php $num_articles = mysql_num_rows($rsc); ?>
					<!-- Page list -->
					<div class="featBox>">
						<ul id="pageList">
							<li id="desc">Pages:</li>
							<?php if ($p != 1) // Previous page arrow
									echo '<li class="arrow"><a href="user/'.$user.'/'.($p-1).'/">&#171;</a></li>';
									
								$pages = ceil(($articles-ARTICLES_PER_USER_PAGE)/ARTICLES_PER_USER_PAGE)+1;
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
										echo (($p==$i)?'<li class="selected">':('<li><a href="user/'.$user.'/'.$i.'/">')).$i.(($p==$i)?'</li>':'</a></li>');
								} else {
									echo '<li class="selected">1</li>';
								}
								if ($p != $pages) // Next page arrow
									echo '<li class="arrow"><a href="user/'.$user.'/'.($p+1).'/">&#187;</a></li>';
							?>
						</ul>
					</div>
					<div class="clear"></div>
				<?php } ?>
		</div>
		<div class="clear"></div>
	<?php } else {
		include('404cont.php');
		}
	?>
	</div>
	<!-- End of user wrapper -->
