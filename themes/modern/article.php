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

$theme->render('components/globals/header', $header);

?>
		<div class="row article-row">
			<div class="small-12 columns title-row">
				<div class="article-title-area">
					<?php $theme->render('components/helpers/breadcrumbs', array('origin' => $article, 'type' => 'article')); ?>
					<h2><?php echo $article->getTitle(); ?></h2>
					<h3><?php echo $article->getTeaser(); ?></h3>
				</div>
			</div>
			<div class="small-12 large-4 large-push-8 columns">
				<?php $theme->render('components/article/meta_info', array('article' => $article)); ?>

				<?php if($isSectionEditor): ?>
					<div class="article-edit"><b><a class="button tiny radius" href="<?php echo ADMIN_URL; ?>?page=addarticle&amp;article=<?php echo $article->getId(); ?>">Edit Article</a></b></div>
				<?php endif; ?>

				<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => $article, 'section' => false)); ?>

				<?php $theme->render('components/article/meta_share', array('article' => $article, 'hidetitle' => false)); ?>
			</div>
			<div class="small-12 large-8 large-pull-4 columns">
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
								<?php echo $article->getImgCaption();?>
							<?php } ?>
							<?php if($image->getAttribution()) { ?>
								<div class="imageAttr">
									<span>
									<?php if($image->getAttrLink()) { ?>
										<a href="<?php echo $image->getAttrLink(); ?>">
									<?php } ?>
									Credit: <?php echo $image->getAttribution();?>
									<?php if($image->getAttrLink()) echo '</a>'?>
									</span>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<?php } ?>
				<?php if($polls): ?>
					<?php foreach($polls as $poll): ?>
						<?php $theme->render('components/article/main_poll', array('poll' => $poll, 'bottom' => false)); ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<div class="article-text">
				<?php
					echo $text;
				?>
				</div>
				<?php if($polls): ?>
					<?php foreach($polls as $poll): ?>
						<?php $theme->render('components/article/main_poll', array('poll' => $poll, 'bottom' => true)); ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<!-- End of content -->
			</div>
		</div>
		<div class="row article-row">
			<div class="small-12 large-4 large-push-8 columns">
				<?php $theme->render('components/article/meta_share', array('article' => $article, 'hidetitle' => false)); ?>

				<?php $theme->render('components/helpers/block_popular'); ?>
			</div>

			<div class="small-12 large-8 large-pull-4 columns">
				<div class="comment-header-block info-box" id="commentHeader">
					<h1><span class="glyphicons glyphicons-comments"></span> Have your say</h1>
					<?php if ($article->canComment($currentuser)) { ?><span class="article-comments-say">Have something to say? <a href="<?php echo Utility::currentPageURL().'#commentForm';?>"><b>Post your comment now</b></a>.</span><?php } ?>
				</div>
				<!-- Comments container -->
				<?php
					$comments = $article->getValidatedComments();

					if (is_array($comments)) {
						foreach($comments as $key => $comment) {
							if(!is_null($comment->getReply())) {
								continue;
							}
							$theme->render('components/article/main_comment', array('comment' => $comment));
						}
					}
				?>
				<!-- End of comments container -->
				
				<!-- Comment form -->
				<?php if ($article->canComment($currentuser)) { ?>
				<div class="new-comment" id="commentForm">
					<h4>Submit your comment</h4>
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<p>You are not logged in. Logged in users may still remain anonymous.</p>
					<?php } else { ?>
						<p>You are logged in as <a href="<?php echo $currentuser->getURL();?>" title="Profile Page"><?php echo $currentuser->getName();?></a>, however you may comment anonymously.</p>
					<?php } ?>
					
					<p>Your comment is submitted in agreement to our <a href="#" data-reveal-id="commentPolicy">commenting policy</a>, which also sets out how we moderate comments.</p>

					<?php if(isset($comment_status)): ?>
					<!-- Errors -->
					<div class="alert-box alert">
						<?php echo $comment_status; ?>
					</div>
					<?php endif; ?>

					<?php if($comment_validate_email): ?>
					<!-- Email Validation -->
					<div class="alert-box alert">
						Thank you for your comment, you now need to validate your email before your comment will show up. Check your inbox for a validation code.
						<?php $_POST = array(); /* Clear form */ ?>
					</div>
					<?php endif; ?>

					<div class="alert-box ajax-comment-error alert" style="display: none;">
					</div>

					<form method="post" action="<?php echo Utility::currentPageURL();?>#commentForm" class="comment-form">
						<div class="row">
							<div class="small-12 columns">
								<div class="row">
									<div class="medium-2 columns">
										<label for="name" class="inline">Your name</label>
									</div>
									<div class="medium-9 columns end">
										<input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])): echo $_POST['name']; elseif($currentuser->isLoggedIn()): echo $currentuser->getName(); endif;?>">
										<p id="name-help" class="input-help">We'll pick "Anonymous" if you don't provide us with one</p>
									</div>
								</div>
								<?php if (!$currentuser->isLoggedIn()) { ?>
								<div class="row">
									<div class="medium-2 columns">
										<label for="email" class="inline">Email address</label>
									</div>
									<div class="medium-9 columns end">
										<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])): echo $_POST['email']; endif;?>">
										<p id="email-help" class="input-help">We will not show this, but we may send you a link to verify your email</p>
									</div>
								</div>
								<?php } else { ?>
									<input type="hidden" name="email" value="<?php if($currentuser->isLoggedIn()): echo $currentuser->getEmail(); endif;?>">
								<?php } ?>
								<div class="row">
									<div class="medium-2 columns">
										<label class="inline" for="comment">Comment</label>
									</div>
									<div class="medium-9 columns end">
										<?php if($_POST['replyComment']): ?>
										<div id="commentReply">
											<span id="replyLink"><b>Replying to:</b> <?php echo $_POST['replyName']; ?> at <?php echo $_POST['replyDate']; ?></span> 
											<a href="#" id="removeReply">
												<span class="glyphicons glyphicons-circle-remove" title="Remove reply"></span>
											</a>
											<input type="hidden" id="replyComment" name="replyComment" value="<?php echo $_POST['replyComment']; ?>"/>
											<input type="hidden" id="replyDate" name="replyDate" value="<?php echo $_POST['replyDate']; ?>"/>
										</div>
										<?php endif; ?>
										<textarea name="comment" id="comment" rows="4"><?php if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
									</div>
								</div>
								<input type="hidden" name="article" value="<?php echo $article->getId(); ?>">
								<div class="row">
									<div class="medium-2 columns">
										&nbsp;
									</div>
									<div class="medium-9 columns end">
										<input type="submit" class="button small radius" value="Submit comment">
									</div>
								</div>
							</div>
						</div>
					</form>

					<div class="comment-form-spin" style="display: none;">
						<img src="<?php echo STANDARD_URL; ?>/img/loading.gif" alt="Loading">
					</div>

					<input type="hidden" name="new-token" id="new-token" value="<?php echo Utility::generateCSRFToken('new_comment'); ?>"/>
				</div>
				<?php } else { ?>
					<div class="alert-box">
						<b>This article is now closed for new comments. Logged in users may still be able to comment.</b>
					</div>
				<?php } ?>
				<!-- End of comment form -->
			</div>
			<input type="hidden" name="poll-token" id="poll-token" value="<?php echo Utility::generateCSRFToken('poll_vote'); ?>"/>

			<?php $theme->render('components/modals/box_abuse'); ?>
			<!-- End of comments -->
		</div>

<?php $theme->render('components/globals/footer'); ?>
