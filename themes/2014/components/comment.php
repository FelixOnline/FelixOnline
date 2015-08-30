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
			<?php if(!$comment->isRejected()) { ?> • 
				<input type="hidden" name="token-rate-<?php echo $comment->getId();?>" id="token-rate-<?php echo $comment->getId();?>" value="<?php echo Utility::generateCSRFToken($comment->getId().'ratecomment'); ?>"/>
				<span id="comment-<?php echo $comment->getId(); ?>-like">
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<a href="#" data-reveal-id="loginModal" class="likeComment"><b>LIKE</b></a>
					<?php } else {
						if ($comment->userLikedComment($currentuser)) echo '<b>YOU LIKED THIS</b>';
						elseif ($comment->userDislikedComment($currentuser)) echo '<b>LIKES</b>';
						else {?>
							<a href="<?php echo Utility::currentPageURL();?>#" id="like"><b>LIKE</b></a>
					<?php } } ?>
					<span id="likecounter">(<?php echo $comment->getLikes(); ?>)</span>
					 • 
				</span>
				<span id="comment-<?php echo $comment->getId(); ?>-dislike">
					<?php if (!$currentuser->isLoggedIn()) { ?>
						<a href="#" data-reveal-id="loginModal" class="dislikeComment"><b>DISLIKE</b></a>
					<?php } else {
						if ($comment->userDislikedComment($currentuser)) echo '<b>YOU DISLIKED THIS</b>';
						elseif ($comment->userLikedComment($currentuser)) echo '<b>DISLIKES</b>';
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
				 • 
				<a href="<?php echo Utility::currentPageURL().'#comment'.$comment->getId(); ?>" id="<?php echo $comment->getId();?>" class="reportAbusive"><b>REPORT COMMENT</b></a>
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
						echo '<b class="comment-reply"><a href="'.Utility::currentPageURL().'#comment'.$reply_comment->getId().'">@'.$reply_comment->getName().'</a>: </b>';
					}
				} catch(Exception $e) {}
				echo $comment->getContent(); 
			}
		?>
		</p>
	</div>
</div>
