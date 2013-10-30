<?php
    /*
     * Comment form template
     */
?>
<div id="commentForm">
    <?php

    // Error diplaying
    if ($errorinsert) { // error on inserting comment
        echo '<div class="commenterror">System error - please email <a href="mailto"felix@imperial.ac.uk@>felix@imperial.ac.uk</a>!</div>';
        echo '<script>location.href=location.href + "#commentForm";</script>';
    }

    if ($errorduplicate) { // comment is a duplicate
        echo '<div class="commenterror">Duplicate comment submitted.</div>';
        echo '<script>location.href=location.href + "#commentForm";</script>';
    }

    if ($errorakismet) { // akismet down
        echo '<div class="commenterror">The spam detection service is currently unavailable, please try submitting your comment later.</div>';
        echo '<script>location.href=location.href + "#commentForm";</script>';
    }

    if ($errorspam) { // failed spam catch
        echo '<div class="commenterror">This comment has been detected as spam. If you are certain this comment is not spam, please contact us - do not try to submit your comment again.</div>';
        echo '<script>location.href=location.href + "#commentForm";</script>';
    }

    if (!$uname) { ?>
        <h5>Comment anonymously or <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox">log in</a></h5>
        <div id="info">
            <p>Anonymous comments are moderated before appearing on the website, and are also submitted to Akismet to cut down on spam. Comments posted while logged in appear immediately and are moderated later. In addition, if you are logged in, your comment will not be sent to Akismet. Read our <a href="<?php echo curPageURLNonSecure(); ?>#commentPolicy" rel="facebox">commenting policy</a> for more information.</p>
        </div>
    <?php } else { ?>
        <h5>Leave a comment as <a href="user/<?php echo $uname;?>/" title="Profile Page"><?php echo get_vname();?></a></h5>
    <?php } ?>
    <form method="post" action="<?php echo curPageURLNonSecure();?>">
        <?php if (!$uname) { ?>
            <label for="name">Name: </label>
            <input name="name" id="name" value="<?php if(isset($_POST['name'])) echo $_POST['name'];?>"/>
            <div class="clear"></div>
        <?php } else { ?>
            <input type="hidden" value="<?php echo $uname; ?>"/>
        <?php } ?>
        <div id="comentbox">
            <label for="comment" id="commentLabel">Comment: </label>
            <div class="clear"></div>
            <textarea name="comment" id="comment" rows="4" class="required"><?php if(isset($_POST['comment'])) echo $_POST['comment']; ?></textarea>
            <label for="comment" class="error">Please write a comment</label>
        </div>
        <div class="clear"></div>
        <input type="submit" value="Post your comment" id="submit" name="<?php if($uname) echo 'articlecomment'; else echo 'articlecomment_ext';?>"/>
    </form>
    <!-- Commenting Policy -->
    <?php include('views/comments/commentPolicy.php'); ?>
</div>
