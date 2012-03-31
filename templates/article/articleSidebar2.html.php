<div class="sidebar2 grid_2 push_6 entry-unrelated">
    <div id="sharebuttonsCont">
        <h6>Sharing</h6>
        <ul>
            <div id="sharebuttons">
                <li id="facebookLike">
                </li>
                <li id="twitterShare">
                </li>
                <li id="googleShare">
                    <g:plusone size="medium"></g:plusone>
                </li>
                <li id="diggShare">
                </li>
            </div>
        </ul>
    </div>
    <ul class="metaList">
        <li id="comments">
            <a href="<?php echo curPageURLNonSecure().'#commentHeader';?>">
                <?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?>
            </a>
        </li>
        <!--<li><a href="<?php echo curPageURLNonSecure();?>#emailArticle" rel="facebox">Email Article</a></li>-->
        <li>
            <a href="print.php?article=<?php echo $article;?>" target="_blank">Print Article</a>
        </li>
    </ul>
</div>
