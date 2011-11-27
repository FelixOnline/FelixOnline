<?php
/*
 * New external comment email template
 * 
 * Requires $comment as comment object
 */
?>
<p>A new comment on the post "<?php echo get_article_title($comment->article); ?>" is waiting for your approval. </br>
<?php echo full_article_url($comment->article); ?>
</p>

<p>
    Author: <?php echo stripslashes($comment->name); ?> (IP: <?php echo $_SERVER['REMOTE_ADDR'];?>)</br>
    Whois: http://ip-whois-lookup.com/lookup.php?ip=<?php echo $_SERVER['REMOTE_ADDR']; ?>
</p>

<p>
    Comment:</br>
    "<?php echo $comment->getContent(); ?>"
</p>

<p>
    Approve it: <?php echo STANDARD_URL."engine/?page=comment&action=approve&c=".$this->getID(); ?></br>
    Trash it: <?php echo STANDARD_URL."engine/?page=comment&action=trash&c=".$this->getID(); ?></br>
    Spam it: <?php echo STANDARD_URL."engine/?page=comment&action=spam&c=".$this->getID();?>
</p>

<p>There are <?php echo get_comments_to_approve(); ?> comment(s) waiting to be approved. View them here: <?php echo BASE_URL."engine/?page=comment"; ?></p>

<p>Felix Online</p>
