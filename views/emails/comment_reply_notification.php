<?php
/*
 * Comment reply notification template
 * 
 * Requires $comment as comment object and $reply as the comment object of the comment that is replying to $comment
 */
$firstname = explode(' ', get_vname_by_uname_db($comment->getUser()));
?>
<p>
    Hi <?php echo $firstname[0]; ?>
</p>

<p>
<?php 
    if($reply->isExternal()) {
        echo $reply->getName();
    } else { ?>
        <a href="<?php echo STANDARD_URL.'user/'.$reply->getUser(); ?>"><?php echo $reply->getName(); ?></a>
<?php } ?>
 has replied to your comment on <a href="<?php echo full_article_url($reply->getArticle()).'#comment'.$reply->getID(); ?>"><?php echo get_article_title($reply->getID()); ?></a> with: 
</p>

<p>
    "<?php echo $reply->getContent(); ?>"
</p>

<p>
    <a href="<?php echo full_article_url($reply->getArticle()).'#comment'.$reply->getID(); ?>">View Comment</a>
</p>

<p>Lots of love,</br>
Felix</p>
