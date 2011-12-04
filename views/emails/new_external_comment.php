<?php
/*
 * New external comment email template
 * 
 * Requires $comment as comment object
 */
?>
<p>A new comment on the post "<?php echo get_article_title($comment->getArticle()); ?>" is waiting for your approval. </br>
<a href="<?php echo full_article_url($comment->getArticle()); ?>"/><?php echo full_article_url($comment->getArticle()); ?></a>
</p>

<p>
    Author: <?php echo stripslashes($comment->getName()); ?> (IP: <?php echo $_SERVER['REMOTE_ADDR'];?>)</br>
    Whois: <a href="http://ip-whois-lookup.com/lookup.php?ip=<?php echo $_SERVER['REMOTE_ADDR']; ?>"/>http://ip-whois-lookup.com/lookup.php?ip=<?php echo $_SERVER['REMOTE_ADDR']; ?></a>
</p>

<p>
    Comment:</br>
    "<?php echo $comment->getContent(); ?>"
</p>

<p>
    Approve it: <a href="<?php echo STANDARD_URL."engine/?page=comment&action=approve&c=".$this->getID(); ?>"><?php echo STANDARD_URL."engine/?page=comment&action=approve&c=".$this->getID(); ?></a></br>
    Trash it: <a href="<?php echo STANDARD_URL."engine/?page=comment&action=trash&c=".$this->getID(); ?>"><?php echo STANDARD_URL."engine/?page=comment&action=trash&c=".$this->getID(); ?></a></br>
    Spam it: <a href="<?php echo STANDARD_URL."engine/?page=comment&action=spam&c=".$this->getID();?>"><?php echo STANDARD_URL."engine/?page=comment&action=spam&c=".$this->getID();?></a>

</p>

<p>There are <?php echo get_comments_to_approve(); ?> comment(s) waiting to be approved. View them here: <a href="<?php echo STANDARD_URL."engine/?page=comment"; ?>"><?php echo STANDARD_URL."engine/?page=comment"; ?></a></p>

<p>Felix Online</p>
