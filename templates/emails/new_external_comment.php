<?php
/*
 * New external comment email template
 * 
 * Requires $comment as comment object
 */
?>
<p>A new comment on the post "<?php echo $comment->getArticle()->getTitle(); ?>" is waiting for your approval. </br>
<a href="<?php echo $comment->getArticle()->getURL(); ?>"/><?php echo $comment->getArticle()->getURL(); ?></a>
</p>

<p>
	Author: <?php echo $comment->getName(); ?> (IP: <?php echo $_SERVER['REMOTE_ADDR'];?>)</br>
	Whois: <a href="http://ip-whois-lookup.com/lookup.php?ip=<?php echo $_SERVER['REMOTE_ADDR']; ?>"/>http://ip-whois-lookup.com/lookup.php?ip=<?php echo $_SERVER['REMOTE_ADDR']; ?></a>
</p>

<p>
	Comment:</br>
	"<?php echo $comment->getContent(); ?>"
</p>

<p>
	Approve it: <a href="<?php echo STANDARD_URL."engine/?page=comment&action=approve&c=".$this->getId(); ?>"><?php echo STANDARD_URL."engine/?page=comment&action=approve&c=".$this->getId(); ?></a></br>
	Trash it: <a href="<?php echo STANDARD_URL."engine/?page=comment&action=trash&c=".$this->getId(); ?>"><?php echo STANDARD_URL."engine/?page=comment&action=trash&c=".$this->getId(); ?></a></br>
	Spam it: <a href="<?php echo STANDARD_URL."engine/?page=comment&action=spam&c=".$this->getId();?>"><?php echo STANDARD_URL."engine/?page=comment&action=spam&c=".$this->getId();?></a>

</p>

<p>There are <?php echo $comment->getCommentsToApprove(); ?> comment(s) waiting to be approved. View them here: <a href="<?php echo STANDARD_URL."engine/?page=comment"; ?>"><?php echo STANDARD_URL."engine/?page=comment"; ?></a></p>

<p>Felix Online</p>
