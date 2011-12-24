<div class="sidebar grid_4 push_8">
    <?php
        // Initialise featured articles
        $sql = "SELECT `top_sidebar_1`,`top_sidebar_2`,`top_sidebar_3`,`top_sidebar_4` FROM `category` WHERE cat='".$article->getCategoryCat()."'";
        $top_articles = $db->get_row($sql);
    ?>
    <div id="featuredBox" <?php if($article->getCategoryCat() == 'phoenix') echo 'class="featboxphoenix"';?>>
        <?php if($article->getCategoryCat() == 'phoenix') { ?>
            <h3><?php echo $article->getCategoryLabel();?></h3>
            <ul>
                <li><a href="phoenix/act1/">Act I</a></li>
                <li><a href="phoenix/act2/">Act II</a></li>
                <li><a href="phoenix/act3/">Act III</a></li>
            </ul>
        <?php } else { ?>
            <h3>Featured <span class="<?php echo $article->getCategoryCat();?>"><?php echo $article->getCategoryLabel();?></span> Stories</h3>
            <ul>
            <?php
                foreach($top_articles as $key => $value) { 
                    $ta = new Article($value);
                    if($key == 'top_sidebar_1') { ?>
                        <li class="withPic">
                            <a href="<?php echo $ta->getURL(); ?>">
                                <h5><?php echo $ta->getTitle();?></h5>
                                <div class="featuredPic">
                                    <a href="<?php echo $ta->getURL(); ?>">
                                        <img id="featuredPhoto" alt="<?php echo get_img_title($ta->getImg1());?>" src="<?php echo get_img_url($ta->getImg1(), 150, 100); //[TODO] ?>">
                                    </a>
                                </div>
                            </a>
                            <div class="clear"></div>
                        </li>
                <?php } else { ?>
                    <li>
                        <a href="<?php echo $ta->getURL(); ?>"><?php echo $ta->getTitle();?></a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>
    <?php
        include_once('sidebar/socialLinks.php');
        include_once('sidebar/mostPopular.php');
        include_once('sidebar/mediaBox.php');
        include_once('sidebar/fbActivity.php');
    ?>
</div>
