<?php
	/*
	 * A single comment template
	 *
	 * Needs $comment to be a comment object
	 */
?>
<div class="article-comment <?php if($comment->isRejected()) echo ' rejected'; ?>" id="<?php echo $comment->getId();?>">
	<div class="row">
		<div class="medium-8 columns<?php if($comment->isRejected()) { ?> end<?php } ?>">
			<div class="comment-meta" id="<?php echo $comment->getId();?>">
				<div class="comment-author">
					<span class="glyphicons glyphicons-user"></span> <span class="comment-author-name"><?php echo $comment->getName(); ?></span>
				</div>
				<div class="comment-date">
					<span class="glyphicons glyphicons-clock"></span> <span class="comment-date-date"><?php echo date('l F d Y H:i', $comment->getTimestamp()); ?></span>
				</div>
			</div>
		</div>
		<?php if(!$comment->isRejected()) { ?>
		<div class="medium-4 columns">
			<input type="hidden" name="token-rate-<?php echo $comment->getId();?>" id="token-rate-<?php echo $comment->getId();?>" value="<?php echo Utility::generateCSRFToken($comment->getId().'ratecomment'); ?>"/>
			<div class="comment-actions text-right">
				<?php
					$rating = $comment->getLikes() - $comment->getDislikes();

					if($rating > 0) { $class = 'positive-comment'; }
					elseif($rating < 0) { $class = 'negative-comment'; }
					else { $class = 'neutral-comment'; }

					if($rating > 0) {
						$rating = '+'.$rating;
					}
				?>
				<span class="comment-rating <?php echo $class; ?>"><?php echo $rating; ?></span>

				<span class="comment-like">
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<a href="#" data-reveal-id="loginModal" class="logout-like">
							<span class="glyphicons glyphicons-thumbs-up"></span>
						</a>
					<?php } else { ?>
						<?php if ($comment->userLikedComment($currentuser)): ?>
							<a data-tooltip aria-haspopup="true" class="has-tip" title="You have up-voted this">
								<span class="glyphicons glyphicons-thumbs-up blue-thumb"></span>
							</a>
						<?php elseif ($comment->userDislikedComment($currentuser)): echo ''; ?>
						<?php else: ?>
							<a href="<?php echo Utility::currentPageURL();?>#" class="login-like">
								<span class="glyphicons glyphicons-thumbs-up"></span>
							</a>
						<?php endif;
						}
					?>
				</span>

				<span class="comment-dislike">
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<a href="#" data-reveal-id="loginModal" class="logout-dislike">
							<span class="glyphicons glyphicons-thumbs-down"></span>
						</a>
					<?php } else { ?>
						<?php if ($comment->userDislikedComment($currentuser)): ?>
							<a data-tooltip aria-haspopup="true" class="has-tip" title="You have down-voted this">
								<span class="glyphicons glyphicons-thumbs-down blue-thumb"></span>
							</a>
						<?php elseif ($comment->userLikedComment($currentuser)): echo ''; ?>
						<?php else: ?>
							<a href="<?php echo Utility::currentPageURL();?>#" class="login-dislike">
								<span class="glyphicons glyphicons-thumbs-down"></span>
							</a>
						<?php endif;
						}
					?>
				</span>

				<span class="comment-spin" style="display: none;">
					<img src="<?php echo STANDARD_URL; ?>/img/loading.gif" alt="Loading">
				</span>

				<?php if ($article->canComment($currentuser)) { ?>
				<a data-tooltip aria-haspopup="true" class="has-tip comment-reply" title="Reply to" href="<?php echo Utility::currentPageURL().'#'.$comment->getId(); ?>">
					<span class="glyphicons glyphicons-quote"></span>
				</a>
				<?php } ?>

				<a data-tooltip aria-haspopup="true" class="has-tip comment-abuse" title="Report comment" href="<?php echo Utility::currentPageURL().'#'.$comment->getId(); ?>">
					<span class="glyphicons glyphicons-alert"></span>
				</a>
			</div>
		</div>
		<?php } ?>
	</div>

	<?php
		if($comment->isRejected()) { // if comment rejected ?>
			<i>This comment did not follow our <a href="#" data-reveal-id="commentPolicy">commenting policy</a> and has been rejected</i>
		<?php } else { 
			echo $comment->getContent(); 
		}
	?>
	<div class="comment-replies">
		<?php
		foreach($comment->getValidatedReplies() as $reply) {
			$theme->render('components/article/main_comment', array('comment' => $reply));
		}
		?>
	</div>
</div>
