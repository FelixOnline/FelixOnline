<?php
$timing->log('article page');

$meta = '
	<meta name="twitter:card" content="summary_large_image"/>
	<meta name="twitter:site" content="@feliximperial"/>
	<meta property="og:title" content="'.$article->getTitle().'"/>
	<meta property="og:url" content="'.$article->getURL().'"/>
	<meta property="og:type" content="article"/>
	<meta property="og:locale" content="en_GB"/>
	<meta property="og:description" content="'.$article->getTeaser().'"/>
	<meta property="article:section" content="'.$article->getCategoryLabel().'"/>
	<meta property="article:published_time" content="'.date('c', $article->getDate()).'"/>
';

foreach ($article->getAuthors() as $author) {
	$meta .= '<meta property="article:author" content="'.$author->getURL().'"/>';
}

if($article->getImage()) {
	$meta .= '<meta property="og:image" content="'.$article->getImage()->getURL(600).'"/>';
}
if(!$article->getSearchable()) {
	$meta .= '<meta name="robots" content="noindex">';
}
$header = array(
	'title' => $article->getTitle().' - '.$article->getCategoryLabel().' - '.'Felix Online',
	'meta' => $meta
);

$theme->resources->addCSS(array('print.less'));

$theme->render('header', $header);
?>
<!-- Article wrapper -->
<div class="container_12">
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8">
		<?php
			$theme->render('sidebar/featuredBox');
			$theme->render('sidebar/socialLinks');
			$theme->render('sidebar/mostPopular');
			$theme->render('sidebar/fbActivity');
			$theme->render('sidebar/mediaBox');

			$timing->log('after sidebar');
		?>
	</div>
	<!-- End of sidebar -->

	<div class="article grid_8 pull_4 alpha <?php echo $article->getCategoryCat();?> instapaper_body hentry">
		<!-- Article header -->
		<?php if ($article->getCategoryCat() == 'comment') { ?>
			<div class="grid_5">
				<h2 class="instapaper_title entry-title"><?php echo $article->getTitle(); ?></h2>
				<div class="subHeader"><?php echo $article->getTeaser(); ?></div>
			</div>
			<div class="grid_3 alpha omega" id="commentArticlePic">
				<?php
					$author = $article->getAuthors()[0];
					$image = $author->getImage();
				?>
				<a href="user/<?php echo $author->getUser(); ?>/" title="<?php echo $author->getName(); ?>">
					<img id="articlePic" alt="<?php echo $author->getName(); ?>" src="<?php echo $image->getURL(220, 160); ?>"></a>
			</div>
		<?php } else { ?>
			<h2 class="grid_8 instapaper_title entry-title">
				<?php echo $article->getTitle(); ?>
			</h2>
			<div class="subHeader grid_8">
				<?php echo $article->getTeaser(); ?>
			</div>
		<?php } ?>
		<div class="articleInfo grid_8">
			<p>
				<?php echo Utility::outputUserList($article->getAuthors()); ?>
			</p>
			<p>
				<span class="<?php echo $article->getCategoryCat();?>">
					<a href="<?php echo $article->getCategoryCat();?>/">
						<?php echo $article->getCategoryLabel();?>
					</a>
				</span> - <?php echo date("l F j, Y", $article->getDate());?>
			</p>
			<?php
				$isSectionEditor = false;
				if($article->getCategory()->getEditors() != null) {
					foreach($article->getCategory()->getEditors() as $user) {
						if($currentuser->getUser() == $user->getUser()) {
							$isSectionEditor = true;
						}
					}
				}
			?>
			<?php if($currentuser->getRole() >= 25 || $isSectionEditor): ?>
				<span id="editpage"><a href="<?php echo ADMIN_URL; ?>?page=addarticle&amp;article=<?php echo $article->getId(); ?>">Edit Page</a></span>
			<?php endif; ?>
		</div>
		<!-- End of article header -->

		<?php $timing->log('after article header'); ?>

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
							
						</li>
						<li id="redditShare">
							<script type="text/javascript" src="http://www.reddit.com/static/button/button3.js"></script>
						</li>
					</div>
				</ul>
			</div>
			<ul class="metaList">
				<li id="comments">
					<a href="<?php echo Utility::currentPageURL().'#commentHeader';?>">
						<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
					</a>
				</li>
				<li>
					<a href="#" id="print-article">Print Article</a>
				</li>
			</ul>
		</div>
		<!-- End of Sidebar 2 -->
		<?php $timing->log('after sidebar 2'); ?>

		<!-- Content -->
		<div class="content grid_6 pull_2 omega entry-content">
			<?php if($image = $article->getImage()) { ?>
				<div id="imgCont" class="<?php if($image->isTall()) echo "right";?>">
					<?php if($image->isTall()) { ?>
						<img id="articlePic" class="vertical" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(240);?>">
					<?php } else { ?>
						<img id="articlePic" class="horizontal" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(460);?>">
					<?php } ?>
					<?php if ( $image->getCaption() || $image->getAttribution()) { ?>
						<div id="imageCaption">
							<?php if ($image->getCaption()) { ?> 
								<span><?php echo $image->getCaption();?></span>
							<?php } ?>
							<?php if($image->getAttribution()) { ?>
								<div id="imageAttr">
									<?php if($image->getCaption()) echo ' - ';?>
									<?php if($image->getAttrLink()) { ?>
										<a href="<?php echo $image->getAttrLink(); ?>">
									<?php } ?>
										Credit: <?php echo $image->getAttribution();?>
									<?php if($image->getAttrLink()) echo '</a>'?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<?php $timing->log('after image'); ?>
			<?php
				echo $article->getContent();
			?>
			<?php $timing->log('after content'); ?>
		</div>
		<!-- End of content -->

		<!-- Article share -->
		<div class="articleShare grid_8">
			<ul>
				<li>
					<div id="shareText">Share: </div>
				</li>
				<li>
					<div id="twitterShare2">

					</div>
				</li>
				<li>
					<div id="googleShare2">
						
					</div>
				</li>
				<li>
					<div id="facebookLike2">
						
					</div>
				</li>
			</ul>
		</div>
		<!-- End of article share -->

		<?php $timing->log('beginning of comments');?>
		<!-- Comments -->
		<div class="grid_8 comments" id="commentHeader">
			<h3>Comments <span>(<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>)</span></h3>
			<!-- Comments container -->
			<div id="commentCont">
				<?php
					$comments = $article->getComments();
					if(is_array($comments)) {
						foreach($comments as $key => $comment) {
							$theme->render('comment', array('comment' => $comment));
						}
					}
				?>
			</div>
			<!-- End of comments container -->
			
			<!-- Comment form -->
			<div id="commentForm">
				<?php if (!$currentuser->isLoggedIn()) { ?>
					<h5>Comment anonymously or <a href="<?php echo Utility::currentPageURL();?>#loginBox" rel="facebox">log in</a></h5>
					<div id="info">
						<p>Anonymous comments are moderated before appearing on the website. Comments posted while logged in appear immediately and are moderated later. Your IP address will also be submitted to <a href="http://akismet.com/">Akismet</a> for spam detection purposes.</p>
						<p>Read our <a href="<?php echo Utility::currentPageURL(); ?>#commentPolicy" rel="facebox">commenting policy</a> for more information.</p>
					</div>
				<?php } else { ?>
					<h5>Leave a comment as <a href="<?php echo $currentuser->getURL();?>" title="Profile Page"><?php echo $currentuser->getName();?></a></h5>
				<?php } ?>
				<!-- Errors -->
				<div class="error">
					<?php if(isset($errorduplicate) && $errorduplicate) { ?>
						<p>Looks like the comment you have just submitted is a duplicate. Please write something original and try again.</p>
					<?php } ?>
					<?php if(isset($errorspam) && $errorspam) { ?>
						<p>Our spam filters have flagged your comment as suspicious. If you are not a spammer then please <a href="<?php echo STANDARD_URL.'contact/'; ?>">contact us</a>.</p>
					<?php } ?>
					<?php if(isset($errorinsert) && $errorinsert) { ?>
						<p>Uh oh. Looks like an error has occurred. Hopefully it is just a temporary problem so try submitting your comment again. If that still hasn't done the trick then <a href="<?php echo STANDARD_URL.'contact/'; ?>">contact us</a>.</p>
					<?php } ?>
					<?php if (isset($errorconnection) && $errorconnection) { ?>
						<p>Sorry it looks like we are having trouble with our anti spam service. Please try again later.</p>
					<?php } ?>
				</div>
				<!-- End of errors -->
				<form method="post" action="<?php echo Utility::currentPageURL();?>">
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<label for="name">Name: </label>
						<input name="name" id="name" value="<?php if(isset($_POST['name'])) echo $_POST['name'];?>"/>
						<div class="clear"></div>
					<?php } else { ?>
						<input type="hidden" value="<?php echo $currentuser->getUser(); ?>"/>
					<?php } ?>
					<div id="comentbox" class="clearfix">
						<label for="comment" id="commentLabel">Comment: </label>
						<div class="clear"></div>
						<textarea name="comment" id="comment" rows="4" class="required"><?php if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
						<label for="comment" class="error">Please write a comment</label>
					</div>
					<input type="submit" value="Post your comment" id="submit" name="<?php if($currentuser->isLoggedIn()) echo 'articlecomment'; else echo 'articlecomment_ext';?>"/>
				</form>
				<!-- Commenting policy -->
				<?php $theme->render('commentPolicy');?>
				<!-- End of commenting policy -->
			</div>
			<!-- End of comment form -->
		</div>
		<!-- End of comments -->
		<?php $timing->log('end of comments');?>
	</div>
	<?php $timing->log('end of article content');?>
</div>

<!-- Google plus one script -->
<script type="text/javascript">
	window.___gcfg = {lang: 'en-GB'};
	(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	})();
</script>

<?php $timing->log('end of article');?>
<?php $theme->render('footer'); ?>
