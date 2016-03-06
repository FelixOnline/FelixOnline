<?php
$meta = '
	<meta name="twitter:card" content="summary_large_image"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
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
	if($author->getFacebook()) {
		$meta .= '<meta property="article:author" content="'.$author->getFacebook().'"/>';
	}
}

if($article->getImage()) {
	$meta .= '<meta property="og:image" content="'.$article->getImage()->getURL(600).'"/>';
} else {
	$meta .= '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>';
}

if(!$article->getSearchable()) {
	$meta .= '<meta name="robots" content="noindex">';
}
$header = array(
	'title' => $article->getTitle().' - '.$article->getCategory()->getLabel().' - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => $meta
);

$theme->render('components/globals/header', $header);

?>

<?php if($article->getVideoUrl()): ?>
	<div class="video-row">
		<div class="row article-row">
			<div class="small-12 columns title-row">
				<div class="article-title-area">
					<h2><?php echo $article->getTitle(); ?></h2>
					<h3><?php echo $article->getTeaser(); ?></h3>
				</div>
				<?php
					try {
						echo '<div class="video-box">'.$article->getVideo().'</div>';
					} catch(\FelixOnline\Exceptions\InternalException $e) {
						echo '<div class="alert-box">Could not load video</div>';
					}
				?>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="row main-row top-row title-row article-row">
		<div class="small-12 medium-9 medium-push-3 end columns">
			<h2><?php echo $article->getTitle(); ?></h2>
			<h3><?php echo $article->getTeaser(); ?></h3>
		</div>
	</div>
<?php endif; ?>

<div class="row main-row top-row article-row">
	<div class="small-12 medium-3 columns">
			<?php $theme->render('components/article/meta_info', array('article' => $article)); ?>

			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => $article, 'section' => false)); ?>

			<div class="show-for-medium">
				<?php $theme->render('components/article/topics', array('topics' => $topics)); ?>

				<?php $theme->render('components/article/meta_share', array('article' => $article, 'hidetitle' => false)); ?>
			</div>
	</div>

	<div class="small-12 medium-9 large-7 end columns" id="stickyAnchor">
	<!-- Content -->
		<?php $image = $article->getImage(); ?>
		<?php if($image && !$article->getVideoUrl()) { ?>
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

		<?php $theme->render('components/article/meta_share', array('article' => $article, 'hidetitle' => false)); ?>
		<br>
	</div>
</div>

<div class="row <?php echo $article->getCategory()->getCat(); ?>">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="bar-text" id="commentHeader">Have your say</div>
	</div>
</div>
<div class="row top-row main-row">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<?php
			if ($article->canComment($currentuser)) {
		?>
			<div class="callout secondary article-comments-say">
				Have something to say? <a href="<?php echo Utility::currentPageURL().'#commentForm';?>"><b>Post your comment now</b></a>.
			</div>
		<?php
			}
		?>

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
			<p>Your comment is submitted in agreement to our <a data-open="commentPolicy">commenting policy</a>, which also sets out how we moderate comments.</p>

			<?php if(isset($comment_status)): ?>
			<!-- Errors -->
			<div class="callout alert">
				<?php echo $comment_status; ?>
			</div>
			<?php endif; ?>

			<?php if($comment_validate_email): ?>
			<!-- Email Validation -->
			<div class="callout warning">
				Thank you for your comment, you now need to validate your email before your comment will show up. Check your inbox for a validation code.
				<?php $_POST = array(); /* Clear form */ ?>
			</div>
			<?php endif; ?>

			<div class="callout ajax-comment-error alert" style="display: none;">
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
						<div class="row">
							<div class="medium-2 columns">
								<label for="email" class="inline">Email address</label>
							</div>
							<div class="medium-9 columns end">
								<input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])): echo $_POST['email']; endif;?>" required>
								<p id="email-help" class="input-help">We will not show this, but we may send you a link to verify your email</p>
							</div>
						</div>
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
								<textarea name="comment" id="comment" rows="4" required><?php if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
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
				<p class="text-center"><img src="<?php echo STANDARD_URL; ?>/img/loading.gif" alt="Loading"></p>
			</div>

			<input type="hidden" name="new-token" id="new-token" value="<?php echo Utility::generateCSRFToken('new_comment'); ?>"/>
		</div>
		<?php } else { ?>
			<div class="callout secondary">
				<i>This article is now closed for new comments.</i>
			</div>
		<?php } ?>
		<!-- End of comment form -->
	</div>
	<input type="hidden" name="poll-token" id="poll-token" value="<?php echo Utility::generateCSRFToken('poll_vote'); ?>"/>

	<?php $theme->render('components/modals/box_abuse'); ?>
	<?php $theme->render('components/modals/box_comment_policy'); ?>
	<!-- End of comments -->
</div>

<?php if(count($topics) > 0): ?>
<div class="row <?php echo $article->getCategory()->getCat(); ?>">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="bar-text">More on this</div>
	</div>
</div>
<div class="row top-row main-row">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="row small-up-12 medium-up-2" data-equalizer data-equalize-on="medium">
			<?php
				foreach($topics as $topic) {
			?>
				<div class="columns" data-equalizer-watch>
					<?php $theme->render('components/article/topic_block', array('topic' => $topic)); ?>
				</div>
			<?php
				}
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row <?php echo $article->getCategory()->getCat(); ?>">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="bar-text">Trending stories</div>
	</div>
</div>
<div class="row top-row main-row">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="row small-up-12 medium-up-2">
			<?php
				$viewed_articles = (new \FelixOnline\Core\ArticleManager())->getMostPopular(\FelixOnline\Core\Settings::get('popular_articles'));
				
				if (!is_null($viewed_articles)) {
					foreach($viewed_articles as $article) {
			?>
					<div class="columns">
			<?php
						$theme->render('components/category/block_normal', array(
							'article' => $article,
							'show_category' => true,
							'headshot' => false
						));
			?>
					</div>
			<?php
					}
				} else {
			?>

			<p>It doesn't look like any articles have been read recently...</p>

			<?php
				}
			?>
		</div>
	</div>
</div>

<?php $theme->render('components/globals/footer'); ?>
