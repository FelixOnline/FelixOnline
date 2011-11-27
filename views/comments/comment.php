<?php
    /*
     * A single comment template
     *
     * Needs $comment to be a comment object
     */
?>
<div class="singleComment" id="comment<?php echo $comment->getID();?>">
    <div class="comment">
        <div class="commentInfo">
            <p id="commentUser">
            <?php
                if($comment->isExternal()) { // external comment
                    echo $comment->getName();
                } else { ?>
                    <a href="user/<?php echo $comment->getUser();?>/">
                        <?php echo $comment->getName(); ?>
                    </a>
            <?php 
                } 
                if($comment->byAuthor()) {
                    echo '<span>(Author)</span>'; 
                }
            ?>
            </p>
            <span id="commentDate"><?php echo date('l F d Y H:i',$comment->getTimestamp()); ?></span>
        </div>
        <p>
            <?php 
                // Comment content 
                echo $comment->getContent(); 
            ?>
        </p>
    </div>
    <div class="commentAction" id="<?php echo $comment->getID();?>">
        <ul>
            <li><?php
                if (!is_logged_in()) {?>
                    <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox" class="likeComment">Like</a>
                <?php } else {
                    if (user_like_comment($comment->getID(), is_logged_in())) echo 'Liked'; // if user has already liked or disliked comment then remove link
                    else {?>
                        <a href="<?php echo curPageURLNonSecure();?>#" id="like">Like</a>
                <?php } } ?>
                <span id="likecounter">(<?php echo get_likes($comment->getID());?>)</span>
            </li>
            <li><?php if (!is_logged_in()) { ?>
                <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox" class="dislikeComment">Dislike</a>
                <?php } else {
                    if (user_like_comment($comment->getID(), is_logged_in())) echo 'Disliked';
                    else {?>
                    <a href="<?php echo curPageURLNonSecure();?>#" id="dislike">Dislike</a>
                <?php } }?>
                <span id="dislikecounter">(<?php echo get_dislikes($comment->getID());?>)</span>
            </li>
            <li><a href="<?php echo curPageURLNonSecure().'#comment'.$comment->getID();?>" id="commentLink">Link</a></li>
            <li class="last"><a href="<?php echo curPageURLNonSecure().'#comment'.$comment->getID(); ?>" id="<?php echo $comment->getID();?>" class="replyToComment">Reply to</a></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>
