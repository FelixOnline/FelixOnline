<?php
	/*
	 * A single comment template
	 *
	 * Needs $comment to be a comment object
	 */
?>
<div class="article-comment <?php if($comment->isPending()) echo ' pending'; if($comment->isRejected()) echo ' rejected'; ?>" id="comment<?php echo $comment->getId();?>">
	<div class="comment">
		<h4><a href="<?php echo Utility::currentPageURL().'#comment'.$comment->getId();?>">Untitled comment</a></h4>

		<?php if(!$comment->isRejected() && !$comment->isPending()) { // if internal comment that is rejected ?>
		<div class="comment-tools" id="<?php echo $comment->getId();?>">
			<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken($comment->getId().'ratecomment'); ?>"/>
			<ul>
				<li id="comment-<?php echo $comment->getId(); ?>-like">
				<?php
					if (!$currentuser->isLoggedIn()) {?>
						<a href="#" data-reveal-id="loginModal">Like</a>
					<?php } else {
						if ($liked = $comment->userLikedComment($currentuser)) echo 'Liked'; // if user has already liked or disliked comment then remove link
						else {?>
							<a href="<?php echo Utility::currentPageURL();?>#" id="like">Like</a>
					<?php } } ?>
						<span id="likecounter">(<?php echo $comment->getLikes(); ?>)</span>
				</li>
				<li id="comment-<?php echo $comment->getId(); ?>-dislike"><?php if (!$currentuser->isLoggedIn()) { ?>
					<a href="#" data-reveal-id="loginModal">Dislike</a>
					<?php } else {
						if ($liked) echo 'Disliked';
						else {?>
						<a href="<?php echo Utility::currentPageURL();?>#" id="dislike">Dislike</a>
					<?php } }?>
						<span id="dislikecounter">(<?php echo $comment->getDislikes(); ?>)</span>
				</li>
				<li id="likespinner_<?php echo $comment->getId(); ?>" style="display: none;">
					<span class="loading">Please wait...</span>
				</li>
				<li class="last">
					<a href="<?php echo Utility::currentPageURL().'#comment'.$comment->getId(); ?>" id="<?php echo $comment->getId();?>" class="replyToComment">Reply to</a>
				</li>
			</ul>
		</div>
		<?php } ?>

		<div class="comment-meta small">
			<b><?php echo $comment->getName(); ?></b> • <?php echo date('l F d Y H:i \a\t H:m', $comment->getTimestamp()); ?>
		</div>

		<?php
			if($comment->isRejected()) { // if comment rejected ?>
				<div class="alert-box">This comment did not follow our <a href="<?php echo Utility::currentPageURL(); ?>#commentPolicy" rel="facebox">commenting policy</a> and has been rejected</div>
			<?php } else { 
				// Comment content 
				echo $comment->getContent(); 
			}
		?>
		</p>
	</div>
	<?php if($comment->isPending()) { ?>
		<div class="alert-box">
			This comment is awaiting approval and will appear shortly if it follows our <a href="<?php echo Utility::currentPageURL(); ?>#commentPolicy" rel="facebox">commenting policy</a>. If you have an Imperial ID, you can avoid this delay by <a href="#" data-reveal-id="loginModal">logging in</a> before commenting.
		</div>
	<?php } ?>
</div>
