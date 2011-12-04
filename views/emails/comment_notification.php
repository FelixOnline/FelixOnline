<?php
/*
 * New comment notification (to authors) email template
 * 
 * Requires $comment as comment object
 */
?>
<p>
<?php if(!$comment->isExternal()) { ?>
    <a href="<?php echo STANDARD_URL.'user/'.$comment->getUser();?>"><?php echo $comment->getName(); ?></a>
<?php } else { ?>
    <?php echo $comment->getName(); ?>
<?php } ?>
 has posted a comment on your article, "<a href="<?php echo full_article_url($comment->getArticle()).'#'.$comment->getID(); ?>"><?php echo get_article_title($comment->getArticle());?></a>" with:
</p>
<p>
    "<?php echo $comment->getContent(); ?>"
</p>

<p>
    <a href="<?php echo full_article_url($comment->getID()).'#comment'.$comment->getID(); ?>">View Comment</a>
</p>

<p>Lots of love,
</br>
Felix</p>
