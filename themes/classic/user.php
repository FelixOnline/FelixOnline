<?php
$timing->log('user page');

$meta = '
	<meta property="og:title" content="'.$user->getName().'"/>
	<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>
	<meta property="og:url" content="'.$user->getURL().'"/>
	<meta property="og:type" content="profile"/>
	<meta property="og:locale" content="en_GB"/>
	<meta property="og:description" content="'.$user->getDescription().'"/>
';
if($user->hasArticlesHiddenFromRobots() && $user->getUser!="felix" ) {
	$meta .= '<meta name="robots" content="noindex"/>';
}
$header = array(
	'title' => $user->getName().' - '.'Felix Online',
	'meta' => $meta
);

$theme->render('header', $header);
?>
<div class="container_12 usercontainer">
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8">
		<?php if ($article_count > 2 && $popular_articles) { ?>
			<div id="userPopular">
				<h3>Most Popular Articles</h3>
				<ol>
				<?php foreach($popular_articles as $article) { ?>
					<li id="userPopList">
						<div id="popTitle">
							<?php if($currentuser->isLoggedIn() == $user->getUser()) { ?>
							<div id="popHits">
								<?php echo $article->getHits(); ?> hits
							</div>
							<?php } ?>
							<a href="<?php echo $article->getURL();?>"><?php echo $article->getTitle();?></a>
						</div>
					</li>
				<?php } ?>
				</ol>
			</div>
		<?php } ?>
		<?php if ($comments) { ?>
			<div id="recentComments">
				<h3>Recent Comments</h3>
				<?php if ($popularity = $user->getCommentPopularity()) { ?>
					<span id="popularity">(Popularity: <?php echo $popularity;?>% over <?php echo ($user->getLikes() + $user->getDislikes());?> ratings)</span>
				<?php } ?>
				<ul id="commentList">
					<?php foreach ($comments as $comment) { ?>
						<li>
							<a href="<?php echo $comment->getURL(); ?>"><?php echo $comment->getArticle()->getTitle(); ?></a> <p><?php echo Utility::trimText($comment->getContent(), 130, false); ?></p>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<?php 
			$theme->render('sidebar/fbActivity');
			$theme->render('sidebar/mostPopular');
			$theme->render('sidebar/mediaBox');
		?>
	</div>
	<!-- End of sidebar -->
	<div class="grid_8 pull_4 user omega clearfix">
		<form id="profileform">
			<div id="userInfoCont" class="clearfix">
				<h2>
					<?php echo $user->getName(); ?><span>
						<?php if ($currentuser->getUser() == $user->getUser()) { ?>
							<a href="#" id="editProfile">Edit Profile</a>
							<a href="#" id="editProfileSave" style="display:none;">Save Profile</a>
						<?php } ?>
					</span>
					<span class="loading">Saving...</span>
				</h2>
				<ul id="userInfo">
					<?php $info = $user->getInfo(); ?>
					<li><?php echo $info[0]; ?></li>
					<li><?php echo $info[1]; ?></li>
				</ul>
				<?php if ($articles || $comments) { ?>
					<p><?php if ($articles){ echo 'Author of '.$article_count; ?> article<?php echo ($article_count != 1 ? 's' : '');?><?php } ?>
					<?php if($comments && $articles){?> and <?php } ?>  
					<?php if($comments) { echo $comment_count;?> comment<?php echo ($comments != 1 ? 's' : ''); }?> 
					since <?php echo date('d/m/Y',$user->getFirstLogin());?></p>
					<p>Last login: <?php echo date('d/m/Y',$user->getLastLogin()); ?></p>
				<?php } ?>
			</div>
			<div id="personalCont" class="clearfix" <?php if(!$user->hasPersonalInfo()) echo 'style="display:none;"'; ?>>
				<div id="descCont">
					<?php if($description = $user->getDescription()) {
						echo $description;
					} else if($currentuser->getUser() == $user) {
						echo "Add some personal info....";
					} ?>
				</div>
				<div id="personalLinks">
					<ul>
						<li class="facebook" <?php if(!$user->getFacebook()) echo 'style="display:none;"'; ?>>
							<a href="<?php echo $user->getFacebook(); ?>" target="_blank">Facebook</a>
						</li>
						<li class="twitter" <?php if(!$user->getTwitter()) echo 'style="display:none;"'; ?>>
							<a href="http://www.twitter.com/<?php echo $user->getTwitter(); ?>" target="_blank">@<?php echo $user->getTwitter(); ?></a>
						</li>
						<li class="useremail" <?php if(!$user->getEmail()) echo 'style="display:none;"'; ?>>
							<?php echo Utility::hideEmail($user->getEmail()); ?>
						</li>
						<li class="website" <?php if(!$user->getWebsiteurl()) echo 'style="display:none;"'; ?>>
							<a href="<?php echo $user->getWebsiteurl();?>" target="_blank">
								<?php 
									if($user->getWebsitename()) { 
										echo $user->getWebsitename();
									} else {
										echo $user->getWebsiteurl();
									} 
								?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<?php if($currentuser->isLoggedIn() == $user->getUser()) { ?>
				<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken('userprofile'); ?>"/>
				<div id="personalCont" class="edit clearfix" style="display: none;">
					<div id="descCont">
						<textarea placeholder="Add some personal info..."><?php echo $user->getDescription(); ?></textarea>
					</div>
					<div id="personalLinksEdit">
						<ul>
							<li class="facebook"><input name="facebook" type="text" class="url" value="<?php echo $user->getFacebook(); ?>" placeholder="http://www.facebook.com/joe.bloggs"/></li>
							<li class="twitter">@<input name="twitter" type="text" value="<?php echo $user->getTwitter(); ?>" placeholder="twitter"/></li>
							<li class="useremail"><input name="email" type="text" class="required email" value="<?php echo ($user->getEmail() ? $user->getEmail() : '')?>" placeholder="name@domain.com"/></li>
							<li class="website"><input name="webname" type="text" id="name" value="<?php echo $user->getWebsitename(); ?>" placeholder="Name"/><input type="text" name="weburl" id="url" class="url" value="<?php echo $user->getWebsiteurl(); ?>" placeholder="Url"/></li>
						</ul>
					</div>
				</div>
			<?php } ?>
		</form>
		<?php if (!empty($articles)) { ?>
			<!-- Articles -->
			<div id="articleListCont">
				<h3 id="userArticleTitle">
					Articles <span>
						<a href="rss.php?id=<?php echo $user->getUser();?>" target="_blank" id="userRSS">RSS Feed</a>
					</span>
				</h3>
				<?php foreach($articles as $key => $article) { ?>
					<div class="userArticle clearfix">
						<div class="userArticleDate grid_1 alpha">
							<span><?php echo date('jS',$article->getPublished()); ?></span><br/>
							<?php echo date('F Y',$article->getPublished()); ?>
							<?php if ($currentuser->getUser() == $user->getUser()) { ?>
								<div><?php echo $article->getHits(); ?> hits</div>
							<?php } ?>
						</div>
						<div class="userArticleInfo grid_7 omega second clearfix">
							<h3>
								<a href="<?php echo $article->getURL();?>">
									<?php echo $article->getTitle();?>
								</a>
							</h3>
							<div class="subHeader">
								<p>
									<?php echo $article->getPreview(30); ?>
								</p>
								<div id="storyMeta">
									<ul class="metaList">
										<li id="category">
											<a href="<?php echo $article->getCategory()->getURL();?>" class="<?php echo $article->getCategory()->getCat();?>">
												<?php echo $article->getCategory()->getLabel();?>
											</a>
										</li>
										<?php if($article->getNumComments()) { ?>
											<li id="comments">
												<a href="<?php echo $article->getURL();?>#commentHeader">
													<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
												</a>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>	
				<?php } ?> 
			</div>
			<!-- End of articles -->
			
			<!-- Page list -->
			<div class="grid_8 clearfix">
				<ul id="pageList" class="clearfix">
					<li id="desc">Pages:</li>
					<?php if ($pagenum != 1) { // Previous page arrow ?>
						<li class="arrow">
							<a href="<?php echo $user->getURL($pagenum-1); ?>">
								&#171;
							</a>
						</li>
					<?php } 
						if ($pages > 1) {
							$span = ARTICLES_PER_USER_PAGE;
							if ($pages > $span) { // more pages than limit
								if ($pagenum >= ($span/2)) {
									$start = ($pagenum - $span/2)+1;
									$limit = $pagenum + $span/2;
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
							for ($i=$start;$i<=$limit;$i++) {
								if($pagenum==$i) { ?>
									<li class="selected">
								<?php } else { ?>
									<li>
										<a href="<?php echo $user->getURL($i); ?>">
								<?php } ?>
									<?php echo $i; ?>
								<?php if($pagenum==$i) { ?>
									</li>
								<?php } else { ?>
									</a></li>
								<?php }
							}
						} else { ?>
							<li class="selected">1</li>
						<?php }
						if ($pagenum != $pages) { // Next page arrow ?>
							<li class="arrow">
								<a href="<?php echo $user->getURL($pagenum+1);?>">
									&#187;
								</a>
							</li>
						<?php }
					?>
				</ul>
			</div>
			<!-- End of page list -->
		<?php } ?>
	</div>
</div>
<?php $timing->log('end of user page');?>
<?php $theme->render('footer'); ?>
