<?php
    /*
     * A single comment template
     *
     * Needs $comment to be a comment object
     */
?>
<div class="singleComment<?php if($comment->isPending()) echo ' pending'; if($comment->isRejected()) echo ' rejected'; ?>" id="comment<?php echo $comment->getId();?>">
    <div class="comment">
        <div class="commentInfo">
            <p id="commentUser">
            <?php
                if($comment->isExternal()) { // external comment
                    echo $comment->getName();
                } else { ?>
                    <a href="<?php echo $comment->getUser()->getURL();?>/">
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
    <div class="commentAction" id="<?php echo $comment->getId();?>">
        <ul>
            <li>
            <?php
                if (!$currentuser->isLoggedIn()) {?>
                    <a href="<?php echo Utility::currentPageURL();?>#loginBox" rel="facebox" class="likeComment">Like</a>
                <?php } else {
                    if ($liked = $comment->userLikedComment($currentuser->getUser())) echo 'Liked'; // if user has already liked or disliked comment then remove link
                    else {?>
                        <a href="<?php echo Utility::currentPageURL();?>#" id="like">Like</a>
                <?php } } ?>
                    <span id="likecounter">(<?php echo $comment->getLikes(); ?>)</span>
            </li>
            <li><?php if (!$currentuser->isLoggedIn()) { ?>
                <a href="<?php echo Utility::currentPageURL();?>#loginBox" rel="facebox" class="dislikeComment">Dislike</a>
                <?php } else {
                    if ($liked) echo 'Disliked';
                    else {?>
                    <a href="<?php echo Utility::currentPageURL();?>#" id="dislike">Dislike</a>
                <?php } }?>
                    <span id="dislikecounter">(<?php echo $comment->getDislikes(); ?>)</span>
            </li>
            <li>
                <a href="<?php echo Utility::currentPageURL().'#comment'.$comment->getId();?>" id="commentLink">Link</a>
            </li>
            <li class="last">
                <a href="<?php echo Utility::currentPageURL().'#comment'.$comment->getId(); ?>" id="<?php echo $comment->getId();?>" class="replyToComment">Reply to</a>
            </li>
        </ul>
    </div>
    <?php } ?>
    <div class="clear"></div>
    <?php 
        if($comment->isPending()) { ?>
            <div id="commentPending">
                This comment is awaiting approval and will appear shortly if it follows our <a href="<?php echo Utility::currentPageURL(); ?>#commentPolicy" rel="facebox">commenting policy</a>. If you have an Imperial ID, you can avoid this delay by <a href="<?php echo Utility::currentPageURL(); ?>#loginBox" rel="facebox">logging in</a> before commenting.
            </div>
    <?php } ?>
</div>
