<?php
$header = array(
    'title' => 'Felix Online - The student voice of Imperial College London',
    'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

include($this->directory.'/header.php'); // replace this with function
?>

<div class="container_12">
    <!-- Sidebar -->
    <div class="sidebar grid_4 push_8">
        <?php
            include_once(THEME_DIRECTORY.'/sidebar/fbLikeBox.php');
            //include_once(THEME_DIRECTORY.'/sidebar/mediaBox.php');
            include_once(THEME_DIRECTORY.'/sidebar/socialLinks.php');
            include_once(THEME_DIRECTORY.'/sidebar/fbActivity.php');
            include_once(THEME_DIRECTORY.'/sidebar/mostPopular.php');
            include_once(THEME_DIRECTORY.'/sidebar/iscience.php');
            include_once(THEME_DIRECTORY.'/sidebar/recentcomments.php');
        ?>
    </div>
    <!-- End of sidebar -->
    
    <!-- Front page articles -->
    <div class="grid_8 pull_4 featCont layout1">
        <?php
            // Section a
            $sql = "SELECT 
                `1` as one, 
                `2` as two, 
                `3` as three, 
                `4` as four,
                `5` as five,
                `6` as six,
                `7` as seven,
                `8` as eight 
                FROM `frontpage` 
                WHERE layout='1' 
                AND section='a'";
            $sectionA = $db->get_row($sql);
        ?>
        <!-- Top story -->
        <div class="grid_8 alpha topstory">
            <?php // Initialise top story
                $article = new Article($sectionA->one);
            ?>
            <div class="border clearfix <?php echo $article->getCategoryCat();?>">
                <h2>
                    <a href="<?php echo $article->getURL(); ?>">
                        <?php echo $article->getTitle(); ?>
                    </a>
                </h2>
                <div class="subHeader">
                    <p>
                        <?php echo $article->getPreview(50); ?>
                    </p>
                    <div id="storyMeta" class="<?php if($article->getNumComments()) echo 'extra'; ?>">
                        <ul class="metaList">
                            <?php if($article->getNumComments()) { ?>
                                <li id="comments">
                                    <a href="<?php echo $article->getURL();?>#commentHeader">
                                        <?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li id="category">
                                <a href="<?php echo $article->getCategoryURL();?>" class="<?php echo $article->getCategoryCat();?>">
                                    <?php echo $article->getCategoryLabel();?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="topStoryPic">
                    <a href="<?php echo $article->getURL();?>">
                        <img id="topStoryPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(340, 220); ?>" height="220px" width="340px">
                    </a>
                </div>
            </div>
        </div>
        <!-- End of top story -->

        <!-- In this issue -->
        <div class="grid_2 push_6 alpha omega thisIssue">
            <h5>In this Issue</h5>
            <?php 
                // Section b
                $sql = "SELECT `1` as one ,
                    `2` as two,
                    `3` three,
                    `4` four,
                    `5` five 
                    FROM `frontpage` 
                    WHERE layout='1' 
                    AND section='b'";
                //$sectionB = array_unique(mysql_fetch_array(mysql_query($sql,$cid)));
                $sectionB = $db->get_row($sql);
                foreach($sectionB as $key => $value) {
                    $article = new Article($value); ?>
                    <div class="thisIssueCont <?php if($key == 'one') echo 'top';?>">
                        <a href="<?php echo $article->getURL();?>">
                            <img alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(140, 140);?>" width="140px" height="140px" class="captify" rel="caption2"/>
                            <br class="c"/>
                        </a>
                        <div class="caption1">
                            <a href="<?php echo $article->getURL();?>">
                                <?php echo $article->getShortTitle();?>
                            </a>
                        </div>
                        <div id="caption2">
                            <a href="<?php echo $article->getURL();?>">
                                <?php echo $article->getShortDesc(); ?>
                            </a>
                        </div>
                    </div>
                <? } ?>
        </div>
        <!-- End of in this issue -->
    </div>
    <!-- End of front page articles -->
</div>
<?php include($this->directory.'/footer.php'); ?>
