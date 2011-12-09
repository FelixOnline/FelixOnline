<?php
/*
 * New comment notification (to authors) email template
 * 
 * Requires $comment as comment object
 * and $user as username of person the email is being sent to
 */

$name = explode(' ', get_vname_by_uname_db($user)); 
?>
<p>Hi <?php echo $name[0]; ?></p>
<p>
<?php if(!$comment->isExternal()) { // if internal comment ?>
    <a href="<?php echo STANDARD_URL.'user/'.$comment->getUser();?>/"><?php echo $comment->getName(); ?></a>
<?php } else { ?>
    <?php echo $comment->getName(); ?>
<?php } ?>
 has posted a comment on your article, "<a href="<?php echo full_article_url($comment->getArticle()).'#'.$comment->getID(); ?>"><?php echo get_article_title($comment->getArticle());?></a>" with:
</p>
<p>
    "<?php echo $comment->getContent(); ?>"
</p>

<p>
    <a href="<?php echo full_article_url($comment->getArticle()).'#comment'.$comment->getID(); ?>">View Comment</a>
</p>

<p>Lots of love,
</br>
Felix</p>
