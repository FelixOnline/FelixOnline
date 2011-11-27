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
    }

    if ($errorduplicate) { // comment is a duplicate
        echo '<div class="commenterror">Duplicate comment submitted.</div>';
    }

    if ($errorrecapatcha) { // failed recapatcha
        echo '<div class="commenterror">Failed reCapatcha.</div>';
    }

    if (!$uname) { ?>
        <h5>Comment anonymously or <a href="<?php echo curPageURLNonSecure();?>#loginBox" rel="facebox">Log in</a></h5>
    <?php } else { ?>
        <h5>Leave a comment as <a href="user/<?php echo $uname;?>/" title="Profile Page"><?php echo get_vname();?></a></h5>
    <?php } ?>
    <form method="post" action="<?php echo curPageURLNonSecure();?>">
        <?php if (!$uname) { ?>
            <label for="name">Name: </label>
            <input name="name" id="name"/>
            <div class="clear"></div>
        <?php } else { ?>
            <input type="hidden" value="<?php echo $uname; ?>"/>
        <?php } ?>
        <div id="comentbox">
            <label for="comment" id="commentLabel">Comment: </label>
            <div class="clear"></div>
            <textarea name="comment" id="comment" rows="4" class="required"></textarea>
            <label for="comment" class="error">Please write a comment</label>
        </div>
        <div class="clear"></div>
        <?php if (!$uname) { ?>
            <label for="capatca">To prove you are human: </label>
            <div class="clear"></div>
            <?php
                require_once('inc/recaptchalib.php');
                echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY);
                //A.This div notifies the user whether the Recaptcha was Successful or not
                echo '<label for="recaptcha_response_field" class="error" id="captchaStatus"></label>';
            ?>
        <?php } ?>
        <input type="submit" value="Post your comment" id="submit" name="<?php if($uname) echo 'articlecomment'; else echo 'articlecomment_ext';?>"/>
    </form>
</div>
