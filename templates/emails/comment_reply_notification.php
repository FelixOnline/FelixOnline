<?php
/*
 * Comment reply notification template
 * 
 * Requires $comment as comment object and $reply as the comment object of the comment that is replying to $comment
 */
?>
<p>
	Hi <?php echo $comment->getUser()->getFirstName(); ?>
</p>

<p>
<?php 
	if($reply->isExternal()) {
		echo $reply->getName();
	} else { ?>
		<a href="<?php echo $reply->getUser()->getURL(); ?>"><?php echo $reply->getName(); ?></a>
<?php } ?>
 has replied to your comment on "<a href="<?php echo $reply->getArticle()->getURL().'#comment'.$reply->getId(); ?>"><?php echo $reply->getArticle()->getTitle(); ?></a>" with: 
</p>

<p>
	"<?php echo $reply->getContent(); ?>"
</p>

<p>
	<a href="<?php echo $reply->getArticle()->getURL().'#comment'.$reply->getId(); ?>">View Comment</a>
</p>

<p>Lots of love,</br>
Felix</p>
