<div class="grid_8 comments" id="commentHeader">
    <h3>Comments <span>(<?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?>)</span></h3>
    <a href="<?php echo curPageURLNonSecure().'#commentForm';?>" id="postComment">Post a comment</a>

    <!-- Comments container -->
    <div id="commentCont">
        <?php
            $db->timer_start('comment');
            while ($row = mysql_fetch_array($result)) {
                $comment = new Comment($row['id']);
                include('views/comments/commentSingle.php');
            }
            $ctotaltime = $db->timer_elapsed('comment');
            echo '<!-- Comments where generated in ' .$ctotaltime. ' seconds.-->';
        ?>
    </div>
    
    <!-- Comment form -->
    <?php include('views/comments/commentForm.php'); ?>
</div>
