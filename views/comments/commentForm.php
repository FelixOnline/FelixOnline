<?php
    /*
     * Comment form template
     */
?>
<div id="commentForm">
    <script type="text/javascript">
        var RecaptchaOptions = {
            theme : 'clean'
        };
    </script>
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

    if ($errorrecapatcha) { // failed recapatcha
        echo '<div class="commenterror">Failed reCapatcha. Please try again.</div>';
        echo '<script>location.href=location.href + "#commentForm";</script>';
    }

    if ($errorspam) { // failed spam catch
        echo '<div class="commenterror">This comment has been flagged as spam.</div>';
        echo '<script>location.href=location.href + "#commentForm";</script>';
    }

    if (!$uname) { ?>
        <h5>Comment anonymously or <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox">log in</a></h5>
        <div id="info">
            <p>Anonymous comments are moderated before appearing on the website. Comments posted while logged in appear immediately and moderated later. Read our commenting policy [hyper-linked] for more information.</p>
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
        <?php if (!$uname) { ?>
            <label for="capatca">To prove you are human: </label>
            <div class="clear"></div>
            <?php
                require_once('inc/recaptchalib.php');
                echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY);
            ?>
        <?php } ?>
        <input type="submit" value="Post your comment" id="submit" name="<?php if($uname) echo 'articlecomment'; else echo 'articlecomment_ext';?>"/>
    </form>
</div>
