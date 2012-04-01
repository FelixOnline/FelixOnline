<?php
/*
 * New comment notification (to authors) email template
 * 
 * Requires $comment as comment object
 * and $user as username of person the email is being sent to
 */
?>
<p>Hi <?php echo $user->getFirstName(); ?></p>
<p>
<?php if(!$comment->isExternal()) { // if internal comment ?>
    <a href="<?php echo $comment->getUser()->getURL();?>/"><?php echo $comment->getName(); ?></a>
<?php } else { ?>
    <?php echo $comment->getName(); ?>
<?php } ?>
 has posted a comment on your article, "<a href="<?php echo $comment->getArticle()->getURL().'#'.$comment->getId(); ?>"><?php echo $comment->getArticle()->getTitle();?></a>" with:
</p>
<p>
    "<?php echo $comment->getContent(); ?>"
</p>

<p>
    <a href="<?php echo $comment->getArticle()->getURL().'#comment'.$comment->getId(); ?>">View Comment</a>
</p>

<p>Lots of love,
</br>
Felix</p>
