<?php
	/*
	 * A single comment template
	 *
	 * Needs $comment to be a comment object
	 */
?>
<div class="article-comment <?php if($comment->isPending()) echo ' pending'; if($comment->isRejected()) echo ' rejected'; ?>" id="comment<?php echo $comment->getId();?>">
	<div class="comment">
		<!--<h4><a href="<?php echo Utility::currentPageURL().'#comment'.$comment->getId();?>">Untitled comment</a></h4>-->

		<div class="comment-meta small" id="<?php echo $comment->getId();?>">
			<b class="comment-author"><?php echo $comment->getName(); ?></b> • 
			<?php echo date('l F d Y H:i', $comment->getTimestamp()); ?>
			<?php if(!$comment->isRejected() && !$comment->isPending()) { ?> • 
				<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken($comment->getId().'ratecomment'); ?>"/>
				<span id="comment-<?php echo $comment->getId(); ?>-like">
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<a href="#" data-reveal-id="loginModal"><b>LIKE</b></a>
					<?php } else {
						if ($liked = $comment->userLikedComment($currentuser)) echo '<b>LIKED</b>'; // if user has already liked or disliked comment then remove link
						else {?>
							<a href="<?php echo Utility::currentPageURL();?>#" id="like"><b>LIKE</b></a>
					<?php } } ?>
					<span id="likecounter">(<?php echo $comment->getLikes(); ?>)</span>
					 • 
				</span>
				<span id="comment-<?php echo $comment->getId(); ?>-dislike">
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<a href="#" data-reveal-id="loginModal"><b>DISLIKE</b></a>
					<?php } else {
						if ($liked) echo '<b>DISLIKED</b>';
						else {?>
							<a href="<?php echo Utility::currentPageURL();?>#" id="dislike"><b>DISLIKE</b></a>
					<?php } }?>
					<span id="dislikecounter">(<?php echo $comment->getDislikes(); ?>)</span>
				</span>
				<span id="likespinner_<?php echo $comment->getId(); ?>" style="display: none;">
					<span class="loading"><b>PLEASE WAIT...</b></span>
				</span>
				<?php if ($article->canComment($currentuser)) { ?>
				 • 
				<span>
					<a href="<?php echo Utility::currentPageURL().'#comment'.$comment->getId(); ?>" id="<?php echo $comment->getId();?>" class="replyToComment"><b>REPLY TO</b></a>
				</span>
				<?php } ?>
			<?php } ?>
		</div>

		<?php
			if($comment->isRejected()) { // if comment rejected ?>
				<i>This comment did not follow our <a href="#" data-reveal-id="commentPolicy">commenting policy</a> and has been rejected</i>
			<?php } else { 
				// Comment content 
				try {
					$reply_comment = $comment->getReply();
					if($reply_comment != null) {
						echo '<b class="comment-reply"><a href="<?php echo Utility::currentPageURL();?>#comment<?php echo $reply_comment->getId();?>">@'.$reply_comment->getName().'</a>: </b>';
					}
				} catch(Exception $e) {}
				echo $comment->getContent(); 
			}
		?>
		</p>
	</div>
	<?php if($comment->isPending()) { ?>
		<div class="alert-box">
			This comment is awaiting approval and will appear shortly if it follows our <a href="#" data-reveal-id="commentPolicy">commenting policy</a>. If you have an Imperial ID, you can avoid this delay by <a href="#" data-reveal-id="loginModal">logging in</a> before commenting.
		</div>
	<?php } ?>
</div>
