<?php
    /*
     * A single comment template
     *
     * Needs $comment to be a comment object
     */
?>
<div class="singleComment<?php if($comment->isPending()) echo ' pending'; if($comment->isRejected()) echo ' rejected'; ?>" id="comment<?php echo $comment->getID();?>">
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
        <p class="content">
            <?php 
                if($comment->isRejected()) { // if comment rejected ?>
                    <span id="error">This comment did not follow our <a href="<?php echo curPageURLNonSecure(); ?>#commentPolicy" rel="facebox">commenting policy</a> and has been rejected</span>
            <?php } else { 
                    // Comment content 
                    echo $comment->getContent(); 
                }
            ?>
        </p>
    </div>
    <?php if(!$comment->isRejected() && !$comment->isPending()) { // if internal comment that is rejected ?>
    <div class="commentAction" id="<?php echo $comment->getID();?>">
        <ul>
            <li><?php
                if (!is_logged_in()) {?>
                    <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox" class="likeComment">Like</a>
                <?php } else {
                    if ($comment->userLikedComment(is_logged_in())) echo 'Liked'; // if user has already liked or disliked comment then remove link
                    else {?>
                        <a href="<?php echo curPageURLNonSecure();?>#" id="like">Like</a>
                <?php } } ?>
                    <span id="likecounter">(<?php echo $comment->getLikes(); ?>)</span>
            </li>
            <li><?php if (!is_logged_in()) { ?>
                <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox" class="dislikeComment">Dislike</a>
                <?php } else {
                    if ($comment->userLikedComment(is_logged_in())) echo 'Disliked';
                    else {?>
                    <a href="<?php echo curPageURLNonSecure();?>#" id="dislike">Dislike</a>
                <?php } }?>
                    <span id="dislikecounter">(<?php echo $comment->getDislikes(); ?>)</span>
            </li>
            <li>
                <a href="<?php echo curPageURLNonSecure().'#comment'.$comment->getID();?>" id="commentLink">Link</a>
            </li>
            <li class="last">
                <a href="<?php echo curPageURLNonSecure().'#comment'.$comment->getID(); ?>" id="<?php echo $comment->getID();?>" class="replyToComment">Reply to</a>
            </li>
        </ul>
    </div>
    <?php } ?>
    <div class="clear"></div>
    <?php 
        if($comment->isPending()) { ?>
        <div id="commentPending">
        This comment is awaiting approval and will appear shortly if it follows our <a href="<?php echo curPageURLNonSecure(); ?>#commentPolicy" rel="facebox">commenting policy</a>. If you have an Imperial ID, you can avoid this delay by <a href="<?php echo curPageURLNonSecure(); ?>#loginBox" rel="facebox">logging in</a> before commenting.
        </div>
    <?php } ?>
</div>
