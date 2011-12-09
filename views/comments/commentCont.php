<?php
    $sql = "SELECT id,timestamp FROM (".
        " SELECT comment.id,UNIX_TIMESTAMP(comment.timestamp) AS timestamp FROM `comment` WHERE article=$article AND active=1". // select all internal comments 
        " UNION SELECT comment_ext.id,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND pending=0 AND spam=0". // select external comments that are not spam
        //" UNION SELECT comment_ext.id,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP != '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=0". // select external comments that have been approved and not from current ip
        " UNION SELECT comment_ext.id,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP = '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=1 AND spam=0". // select external comments that are pending and are from current ip
        //" UNION SELECT comment_ext.id,UNIX_TIMESTAMP(comment_ext.timestamp) AS timestamp FROM `comment_ext` WHERE article=$article AND IP = '".$_SERVER['REMOTE_ADDR']."' AND active=1 AND pending=0". // select external comments that have been approve and are from current ip
        ") AS t ORDER BY timestamp ASC LIMIT 500";
    if (!$result = mysql_query($sql,$cid))
        echo mysql_error();
?>
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
