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
	<meta property="article:section" content="'.$article->getCategory()->getLabel().'"/>
';

if ($article->getPublished()) {
	$meta .= '<meta property="article:published_time" content="'.date('c', $article->getPublished()).'"/>';
}

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
	'title' => $article->getTitle().' - '.$article->getCategory()->getLabel().' - '.'Felix Online',
	'meta' => $meta
);

$theme->render('components/header', $header);
?>
		<div class="row felix-pad-top">
			<div class="medium-8 columns">
				<div class="article-title-area">
					<h1><?php echo $article->getTitle(); ?></h1>
					<h2><?php echo $article->getTeaser(); ?></h2>
				</div>
			<!-- Content -->
				<?php if($image = $article->getImage()) { ?>
				<div class="article-image<?php if($image->isTall()) { ?> tall-image<?php } ?>">
					<div class="article-image-image">
					<?php if($image->isTall()) { ?>
						<img class="vertical" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(750);?>">
					<?php } else { ?>
						<img class="horizontal" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(1280);?>">
					<?php } ?>
					</div>

					<?php if ( $image->getCaption() || $image->getAttribution()) { ?>
						<div class="article-image-subcaption">
							<?php if ($image->getCaption()) { ?> 
								<span><?php echo $image->getCaption();?></span>
							<?php } ?>
							<?php if($image->getAttribution()) { ?>
								<div class="imageAttr">
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
				<?php if ($article->getCategory()->getCat() == 'comment') { ?>
					<?php
						$author = $article->getAuthors()[0];
						$image = $author->getImage();
					?>
				<a href="user/<?php echo $author->getUser(); ?>/" title="<?php echo $author->getName(); ?>" class="article-author-image">
					<img id="articlePic" alt="<?php echo $author->getName(); ?>" src="<?php echo $image->getURL(220, 160); ?>">
				</a>
				<?php } ?>
				<div class="article-text">
				<?php
					echo $article->getContent();
				?>
				</div>
				<?php $timing->log('after content'); ?>
				<!-- End of content -->

				<?php $timing->log('beginning of comments');?>
				<!-- Comments -->
				<div class="article-comments-zone article-comments-zone-<?php echo $article->getCategory()->getCat(); ?>" id="commentHeader">
					<h3>Comments <span>(<?php echo $article->getNumComments(); ?>)</span></h3>
					<?php if ($article->canComment($currentuser)) { ?><span class="article-comments-say">Have something to say? <a href="<?php echo Utility::currentPageURL().'#commentForm';?>"><b>Post your comment now</b></a>.</span><?php } ?>

					<!-- Comments container -->
					<?php
						$comments = $article->getComments();
						if (is_array($comments)) {
							foreach($comments as $key => $comment) {
								$theme->render('components/comment', array('comment' => $comment));
							}
						}
					?>
					<!-- End of comments container -->
					
					<!-- Comment form -->
					<div class="article-comment" id="commentForm">
						<?php if ($article->canComment($currentuser)) { ?>
							<?php if (!$currentuser->isLoggedIn()) { ?>
								<h4>Comment anonymously or <a href="#" data-reveal-id="loginModal">log in</a></h4>
								<div id="info">
									<p>Anonymous comments are moderated before appearing on the website. Comments posted while logged in appear immediately and are moderated later. Even if you are logged in, your comment can be published anonymous. Your IP address will also be submitted to <a href="http://akismet.com/">Akismet</a> for spam detection purposes.</p>
									<p>Read our <a href="#" data-reveal-id="commentPolicy">commenting policy</a> for more information.</p>
								</div>
							<?php } else { ?>
								<h4>Leave a comment as <a href="<?php echo $currentuser->getURL();?>" title="Profile Page"><?php echo $currentuser->getName();?></a></h4>
								<p>Your comment will be retrospectively moderated according to our <a href="#" data-reveal-id="commentPolicy">commenting policy</a>.</p>
							<?php } ?>

							<?php if(isset($errorempty) || isset($errorduplicate) || isset($erroremail) || isset($errorspam) || isset($errorinsert) || isset($errorconnection)): ?>
							<!-- Errors -->
							<div class="alert-box">
								<?php if(isset($errorempty) && $errorempty) { ?>
									Don't forget to write a comment.
								<?php } ?>
								<?php if(isset($errorduplicate) && $errorduplicate) { ?>
									Looks like the comment you have just submitted is a duplicate. Please write something original and try again.
								<?php } ?>
								<?php if(isset($erroremail) && $erroremail) { ?>
									You need to give you email address. If you haven't, there's something wrong with what you have given us. Don't worry, this won't be published.
								<?php } ?>
								<?php if(isset($errorspam) && $errorspam) { ?>
									Our spam filters have flagged your comment as suspicious. If you are not a spammer then please <a href="<?php echo STANDARD_URL.'contact/'; ?>">contact us</a>.
								<?php } ?>
								<?php if(isset($errorinsert) && $errorinsert) { ?>
									Uh oh. Looks like an error has occurred. Hopefully it is just a temporary problem so try submitting your comment again. If that still hasn't done the trick then <a href="<?php echo STANDARD_URL.'contact/'; ?>">contact us</a>.
								<?php } ?>
								<?php if (isset($errorconnection) && $errorconnection) { ?>
									Sorry it looks like we are having trouble with our anti spam service. Please try again later.
								<?php } ?>
							</div>
							<!-- End of errors -->
							<?php endif; ?>

							<form method="post" action="<?php echo Utility::currentPageURL();?>#commentForm">
								<div class="row">
									<div class="large-9 small-12 columns">
									<?php if (!$currentuser->isLoggedIn()) { ?>
										<input type="hidden" name="articlecomment_ext" value="1" />
										<div class="row">
											<div class="medium-3 columns">
												<label class="inline" for="name">Name to show</label>
											</div>
											<div class="medium-9 columns">
												<input type="text" name="name" id="right-label" value="<?php if(isset($_POST['name'])) echo $_POST['name'];?>">
											</div>
										</div>
										<div class="row">
											<div class="medium-3 columns">
												<label class="inline" for="email">Email (will not be published)</label>
											</div>
											<div class="medium-9 columns">
												<input type="text" name="email" id="right-label" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
											</div>
										</div>
									<?php } else { ?>
										<input type="hidden" name="name" value="<?php echo $currentuser->getUser(); ?>"/>
										<input type="hidden" name="articlecomment" value="1" />
									<?php } ?>
										<div class="row">
											<div class="medium-3 columns">
												<label class="inline" for="comment">Your comment</label>
											</div>
											<div class="medium-9 columns">
												<textarea name="comment" id="comment" rows="4"><?php if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
											</div>
										</div>
										<div class="row">
											<div class="medium-3 columns">
											</div>
											<div class="medium-4 columns">
												<input type="submit" class="button postfix" value="Post comment">
											</div>
										</div>
									</div>
								</div>
							</form>

							<!-- Commenting policy -->
							<?php $theme->render('components/commentPolicy'); ?>
							<!-- End of commenting policy -->
						<?php } else { ?>
							<br>
							<div class="alert-box">
								<b>Comments are disabled for this article</b>
							</div>
						<?php } ?>
					</div>
					<!-- End of comment form -->
				</div>
				<!-- End of comments -->
				<?php $timing->log('end of comments');?>
				<?php $timing->log('end of article content');?>
			</div>
			<div class="medium-4 columns">
				<div class="article-meta">
					<div class="article-authors">By <b><?php echo Utility::outputUserList($article->getAuthors(), true); ?></b>.</div>
					<div class="article-date">Published on <?php echo $article->getPublished() ? date('l F j, Y \a\t H:i', $article->getPublished()) : "<strong>Not Published</strong>";?>.</div>
					<div class="article-comments"><span class="comment-count"><a href="<?php echo Utility::currentPageURL().'#commentHeader';?>"><?php echo $article->getNumComments().'</a></span> comment'.($article->getNumComments() != 1 ? 's' : '');?>.<?php if ($article->canComment($currentuser)) { ?> <a href="<?php echo Utility::currentPageURL().'#commentForm';?>">Post your own now</a>!<?php } ?></div>
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
						<div class="article-edit"><b><a class="button tiny" href="<?php echo ADMIN_URL; ?>?page=addarticle&amp;article=<?php echo $article->getId(); ?>">Edit Article</a></b></div>
					<?php endif; ?>
				</div>
				<?php $theme->render('sidebar/shareArticle', array('article' => $article)); ?>
				
				<?php $theme->render('sidebar/advert'); ?>

				<?php $theme->render('sidebar/contributionPolicy', array('category' => $article->getCategory())); ?>

				<?php $theme->render('sidebar/mostPopular'); ?>

				<?php $theme->render('sidebar/twitter'); ?>
			</div>
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
<!-- Buffer share button js -->
<script type="text/javascript" src="https://d389zggrogs7qo.cloudfront.net/js/button.js"></script>
<?php $timing->log('end of article');?>
<?php $theme->render('components/footer'); ?>
