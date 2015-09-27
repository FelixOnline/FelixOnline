<?php
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

$theme->render('components/noticeBlock', array('no_frontpage_only' => true));

?>
		<div class="row felix-pad-top">
			<div class="felix-pad-top"><?php $theme->render('components/advert', array('sidebar' => false)); ?></div>
			<div class="medium-8 columns">
				<div class="article-title-area">
					<h1><?php echo $article->getTitle(); ?></h1>
					<h2><?php echo $article->getTeaser(); ?></h2>
				</div>
				<div class="show-for-small-only">
					<?php $theme->render('components/articleMeta', array('article' => $article)); ?>

					<?php $theme->render('sidebar/shareArticle', array('article' => $article, 'hidetitle' => true)); ?>
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

					<?php if ( $article->getImgCaption() || $image->getAttribution()) { ?>
						<div class="article-image-subcaption">
							<?php if ($article->getImgCaption()) { ?> 
								<span><?php echo $article->getImgCaption();?></span>
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
				<?php if($polls): ?>
					<?php foreach($polls as $poll): ?>
						<?php $theme->render('components/poll', array('poll' => $poll, 'bottom' => false)); ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<div class="article-text">
				<?php
					echo $text;
				?>
				</div>
				<?php if($polls): ?>
					<?php foreach($polls as $poll): ?>
						<?php $theme->render('components/poll', array('poll' => $poll, 'bottom' => true)); ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php $theme->render('sidebar/shareArticle', array('article' => $article, 'hidetitle' => true)); ?>

				<!-- End of content -->

				<!-- Comments -->
				<div class="article-comments-zone article-comments-zone-<?php echo $article->getCategory()->getCat(); ?>" id="commentHeader">
					<h3>Comments <span>(<?php echo $article->getNumValidatedComments(); ?>)</span></h3>
					<?php if ($article->canComment($currentuser)) { ?><span class="article-comments-say">Have something to say? <a href="<?php echo Utility::currentPageURL().'#commentForm';?>"><b>Post your comment now</b></a>.</span><?php } ?>

					<!-- Comments container -->
					<?php
						$comments = $article->getValidatedComments();

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
									<p>If you do not log in, your details will be sent to <a href="http://akismet.com/">Akismet</a> for spam detection purposes.</p>
									<p><b>If you do not log in, you will also need to verify your email address by clicking a link we send you.</b> You will only need to do this once per email address.</p>
									<p>Read our <a href="#" data-reveal-id="commentPolicy">commenting policy</a> for more information and for details on how we moderate.</p>
								</div>
							<?php } else { ?>
								<h4>Leave a comment as <a href="<?php echo $currentuser->getURL();?>" title="Profile Page"><?php echo $currentuser->getName();?></a></h4>
								<p>You may still comment anonymously if you wish. Your comment will <b>not</b> be sent to our <a href="http://akismet.com/">Akismet</a> spam detection service.</p>
								<p>Read our <a href="#" data-reveal-id="commentPolicy">commenting policy</a> for more information and for details on how we moderate.</p>
							<?php } ?>

							<?php if(isset($comment_status)): ?>
							<!-- Errors -->
							<div class="alert-box">
								<?php echo $comment_status; ?>
							</div>
							<?php endif; ?>

							<?php if($comment_validate_email): ?>
							<!-- Email Validation -->
							<div class="alert-box">
								Thank you for your comment, you now need to validate your email before your comment will show up. Check your inbox for a validation code.
								<?php $_POST = array(); /* Clear form */ ?>
							</div>
							<?php endif; ?>

							<div class="alert-box ajax-comment-error" style="display: none;">
							</div>

							<form method="post" action="<?php echo Utility::currentPageURL();?>#commentForm" class="comment-form">
								<div class="row">
									<div class="large-9 small-12 columns">
										<div class="row">
											<div class="medium-3 columns">
												<label class="inline" for="name">Name to show</label>
											</div>
											<div class="medium-9 columns">
												<input type="text" name="name" id="right-label" value="<?php if(isset($_POST['name'])): echo $_POST['name']; elseif($currentuser->isLoggedIn()): echo $currentuser->getName(); endif;?>">
											</div>
										</div>
										<?php if (!$currentuser->isLoggedIn()) { ?>
										<div class="row">
											<div class="medium-3 columns">
												<label class="inline" for="email">Email (will not be published)</label>
											</div>
											<div class="medium-9 columns">
												<input type="text" name="email" id="right-label" value="<?php if(isset($_POST['email'])): echo $_POST['email']; endif;?>">
											</div>
										</div>
										<?php } else { ?>
											<input type="hidden" name="email" value="<?php if($currentuser->isLoggedIn()): echo $currentuser->getEmail(); endif;?>">
										<?php } ?>
										<div class="row">
											<div class="medium-3 columns">
												<label class="inline" for="comment">Your comment</label>
											</div>
											<div class="medium-9 columns">
												<?php if($_POST['replyURL']): ?>
												<div id="commentReply">
													<a href="<?php echo $_POST['replyURL']; ?>" id="replyLink">@<?php echo $_POST['replyName']; ?></a> <a href="#" id="removeReply"><img src="img/x_11x11.png" title="Remove reply"/></a>
													<input type="hidden" id="replyURL" name="replyURL" value="<?php echo $_POST['replyURL']; ?>"/>
													<input type="hidden" id="replyName" name="replyName" value="<?php echo $_POST['replyName']; ?>"/>
													<input type="hidden" id="replyComment" name="replyComment" value="<?php echo $_POST['replyComment']; ?>"/>
												</div>
												<?php endif; ?>
												<textarea name="comment" id="comment" rows="4"><?php if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
											</div>
										</div>
										<input type="hidden" name="article" value="<?php echo $article->getId(); ?>">
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

							<div class="comment-form-spin" style="display: none;">
								<img src="<?php echo STANDARD_URL; ?>/img/loading.gif" alt="Loading">
							</div>

							<input type="hidden" name="new-token" id="new-token" value="<?php echo Utility::generateCSRFToken('new_comment'); ?>"/>

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

				<input type="hidden" name="poll-token" id="poll-token" value="<?php echo Utility::generateCSRFToken('poll_vote'); ?>"/>

				<div id="abuseModal" class="reveal-modal medium" data-reveal>
					<div class="row">
						<div class="medium-12 small-12 columns">
							<h3>Report comment</h3>
							<p id="abuseModalBlurb">Would you like to report this comment as being abusive or containing inappropriate content? Felix will investigate all reported comments at the soonest possible opportunity. You will not receive a reply from Felix.</p>
							<p id="abuseModalBlurbResult" style="display: none;"></p>
							<p id="abuseModalBlurbWait" style="display: none;">Please wait...</p>
							<div id="abuseModalButtons" class="text-right">
								<div id="bad-comment-id" style="display: none;"></div>
								<a href="<?php echo Utility::currentPageURL(); ?>#commentHeader" class="button confirmAbusive">Yes</a>
								<a href="<?php echo Utility::currentPageURL(); ?>#commentHeader" class="button closeAbusive">No</a>
							</div>
							<div id="abuseModalButtonsResult" class="text-right" style="display: none;">
								<a href="<?php echo Utility::currentPageURL(); ?>#commentHeader" class="button closeAbusive">Close</a>
							</div>
						</div>
					</div>
				</div>
				<!-- End of comments -->
			</div>
			<div class="medium-4 columns">
				<div class="show-for-medium-up">
					<?php $theme->render('components/articleMeta', array('article' => $article)); ?>
				</div>

				<?php if($isSectionEditor): ?>
					<div class="article-edit"><b><a class="button tiny radius" href="<?php echo ADMIN_URL; ?>?page=addarticle&amp;article=<?php echo $article->getId(); ?>">Edit Article</a></b></div>
				<?php endif; ?>

				<div class="show-for-medium-up">
					<?php $theme->render('sidebar/shareArticle', array('article' => $article, 'hidetitle' => false)); ?>
				</div>
				
				<?php $theme->render('components/advert', array('sidebar' => true)); ?>

				<?php $theme->render('sidebar/contributionPolicy', array('category' => $article->getCategory())); ?>

				<?php $theme->render('sidebar/mostPopular'); ?>

				<?php $theme->render('sidebar/twitter'); ?>
			</div>
		</div>

<?php $theme->render('components/footer'); ?>
