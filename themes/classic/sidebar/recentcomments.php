<?php
	$cache = new Cache('recentComments');
	if($cache->start()) {
?>
<div class="recentComments">
	<h3>Recent Comments</h3>
	<ul>
		<?php 
			$recent_comments = Comment::getRecentComments(RECENT_COMMENTS);
			
			if(!is_null($recent_comments)) {
				foreach($recent_comments as $key => $object) {
					$comment = new Comment($object->id);
			?>
				<li <?php if($key+1 == count($recent_comments)) echo 'class="last"';?>>
					<p id="article">
						On <a href="<?php echo $comment->getArticle()->getURL(); ?>"><?php echo $comment->getArticle()->getTitle();?></a>
					</p>
					<p id="comment">
						<span id="endcomment">
							<?php echo Utility::trimText($comment->getContent(), 120);?>
						</span>
					</p>
					<p id="commentinfo">
						<a href="<?php echo $comment->getArticle()->getURL(); ?>#comment<?php echo $comment->getId();?>" title="Go to comment">
							<?php echo getRelativeTime($comment->getTimestamp());?>
						</a> 
						<span id="commenter">
							<?php
								if($comment->isExternal()) { // external comment
									echo $comment->getName();
								} else { ?>
									<a href="<?php echo $comment->getUser()->getURL();?>">
										<?php echo $comment->getName(); ?>
									</a>
							<?php } ?> 
						</span>
					</p>
				</li>
			<?php }
			} else { ?>
				Nobody has posted any comments yet!
			<?php } ?>
	</ul>
</div>
<?php } $cache->stop(); ?>
<?php $timing->log('after recent comments'); ?>
