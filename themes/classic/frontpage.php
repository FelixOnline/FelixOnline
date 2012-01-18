<?php
$timing->log('frontpage');

$header = array(
    'title' => 'Felix Online - The student voice of Imperial College London',
    'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header); 
$timing->log('after header');
?>

<div class="container_12">
    <!-- Sidebar -->
    <div class="sidebar grid_4 push_8">
        <?php
            include_once(THEME_DIRECTORY.'/sidebar/fbLikeBox.php');
            $timing->log('after fblikebox');
            //include_once(THEME_DIRECTORY.'/sidebar/mediaBox.php');
            //$timing->log('after mediabox');
            include_once(THEME_DIRECTORY.'/sidebar/socialLinks.php');
            $timing->log('after social links');
            include_once(THEME_DIRECTORY.'/sidebar/fbActivity.php');
            $timing->log('after fbactivity');
            include_once(THEME_DIRECTORY.'/sidebar/mostPopular.php');
            $timing->log('after mostpopular');
            //include_once(THEME_DIRECTORY.'/sidebar/iscience.php');
            $timing->log('after iscience');
            include_once(THEME_DIRECTORY.'/sidebar/recentcomments.php');
            $timing->log('after recent comments');
        ?>
    </div>
    <?php $timing->log('after sidebar'); ?>
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
            $timing->log('get frontpage articles');
        ?>
        <!-- Top story -->
        <div class="grid_8 alpha topstory">
            <?php // Initialise top story
                $article = new Article($sectionA->one);
                $timing->log('initialise article');
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
                    <div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra'; ?>">
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
                                <?php echo $article->getTeaser(); ?>
                            </a>
                        </div>
                    </div>
                <? } ?>
        </div>
        <!-- End of in this issue -->

        <!-- Second article -->
        <?php $article = new Article($sectionA->two); ?>
        <div class="grid_6 pull_2 omega alpha featBox <?php echo $article->getCategoryCat();?>">
            <h3>
                <a href="<?php echo $article->getURL();?>">
                    <?php echo $article->getTitle();?>
                </a>
            </h3>
            <div class="subHeader">
                <p>
                    <?php echo $article->getPreview(20); ?>
                </p>
                <div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra'; ?>">
                    <ul class="metaList">
                        <?php if($article->getNumComments()) { ?>
                            <li id="comments">
                                <a href="<?php echo $article->getURL();?>#commentHeader">
                                    <?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
                                </a>
                            </li>
                        <?php } ?>
                        <li id="category">
                            <a href="<?php echo $article->getCategoryURL();?>/" class="<?php echo $article->getCategoryCat();?>">
                                <?php echo $article->getCategoryLabel();?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="secondStoryPic">
                <a href="<?php echo $article->getURL(); ?>">
                    <img id="secondStoryPhoto" alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(220, 160);?>" width="220px" height="160px">
                </a>
            </div>
        </div>
        <!-- End of second article -->

        <!-- Third article -->
        <?php $article = new Article($sectionA->three); ?>
        <div class="grid_6 pull_2 omega alpha featBox <?php echo $article->getCategoryCat();?>" id="last">
            <h3>
                <a href="<?php echo $article->getURL();?>">
                    <?php echo $article->getTitle();?>
                </a>
            </h3>
            <div class="subHeader">
                <p>
                    <?php echo $article->getPreview(20); ?>
                </p>
                <div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra'; ?>">
                    <ul class="metaList">
                        <?php if($article->getNumComments()) { ?>
                            <li id="comments">
                                <a href="<?php echo $article->getURL();?>#commentHeader">
                                    <?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
                                </a>
                            </li>
                        <?php } ?>
                        <li id="category">
                            <a href="<?php echo $article->getCategoryURL();?>/" class="<?php echo $article->getCategoryCat();?>">
                                <?php echo $article->getCategoryLabel();?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="secondStoryPic">
                <a href="<?php echo $article->getURL();?>">
                    <img id="secondStoryPhoto" alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(220, 160);?>" width="220px" height="160px" >
                </a>
            </div>
        </div>
        <!-- End of third article -->

        <!-- Article four and five -->
        <?php 
            $articleA = new Article($sectionA->four);
            $articleB = new Article($sectionA->five);
        ?>
        <div class="grid_6 pull_2 alpha omega featBox bottom">
            <!-- Header -->
            <div class="grid_3 alpha header <?php echo $articleA->getCategoryCat();?>">
                <a href="<?php echo $articleA->getCategoryURL();?>/" class="cat <?php echo $articleA->getCategoryCat();?>">
                    <?php echo $articleA->getCategoryLabel();?>
                </a>
                <h4>
                    <a href="<?php echo $articleA->getURL();?>">
                        <?php echo $articleA->getTitle();?>
                    </a>
                </h4>
            </div>
            <div class="grid_3 omega header <?php echo $articleB->getCategoryCat();?>">
                <a href="<?php echo $articleB->getCategoryURL();?>/" class="cat <?php echo $articleB->getCategoryCat();?>">
                    <?php echo $articleB->getCategoryLabel();?>
                </a>
                <h4>
                    <a href="<?php echo $articleB->getURL();?>">
                        <?php echo $articleB->getTitle();?>
                    </a>
                </h4>
            </div>
            <div class="clear"></div>

            <!-- Pictures -->
            <div id="thirdStoryPic" class="grid_3 alpha">
                <a href="<?php echo $articleA->getURL();?>">
                    <img id="thirdStoryPhoto" alt="<?php echo $articleA->getImage()->getTitle();?>" src="<?php echo $articleA->getImage()->getURL(210, 130);?>" width="210px" height="130px">
                </a>
            </div>
            <div id="thirdStoryPic" class="grid_3 omega">
                <a href="<?php echo $articleB->getURL();?>">
                    <img id="thirdStoryPhoto" alt="<?php echo $articleB->getImage()->getTitle();?>" src="<?php echo $articleB->getImage()->getURL(210, 130);?>" width="210px" height="130px"></a>
            </div>
            <div class="clear"></div>

            <!-- Teaser -->
            <p class="grid_3 alpha">
                <?php echo $articleA->getPreview(25); ?>
            </p>
            <p class="grid_3 omega">
                <?php echo $articleB->getPreview(25); ?>
            </p>
            <div class="clear"></div>

            <!-- Story Meta -->
            <div id="storyMeta" class="grid_3 alpha <?php if(!$articleA->getNumComments()) echo 'extra';?>">
                <ul class="metaList">
                    <?php if($articleA->getNumComments()) { ?>
                        <li id="comments">
                            <a href="<?php echo $articleA->getURL();?>#commentHeader">
                                <?php echo $articleA->getNumComments().' comment'.($articleA->getNumComments() != 1 ? 's' : '');?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div id="storyMeta" class="grid_3 omega <?php if(!$articleB->getNumComments()) echo 'extra';?>">
                <ul class="metaList">
                    <?php if($articleB->getNumComments()) { ?>
                        <li id="comments">
                            <a href="<?php echo $articleB->getURL();?>#commentHeader">
                                <?php echo $articleB->getNumComments().' comment'.($articleB->getNumComments() != 1 ? 's' : '');?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <!-- End of article four and five -->

        <!-- News list -->
        <div class="grid_6 pull_2 alpha omega newsList">
            <ul>
                <?php $article = new Article($sectionA->six); ?>
                <li class="<?php echo $article->getCategoryCat();?>">
                    <h4>
                        <a href="<?php echo $article->getURL();?>" id="title">
                            <?php echo $article->getTitle();?>
                        </a> <a href="<?php echo $article->getCategoryURL();?>/" class="<?php echo $article->getCategoryCat();?>">
                            <span id="category">
                                <?php echo $article->getCategoryLabel();?>
                            </span>
                        </a>
                    </h4>
                    <p>
                        <?php echo $article->getPreview(15);?>
                    </p>
                </li>

                <?php $article = new Article($sectionA->seven); ?>
                <li class="<?php echo $article->getCategoryCat();?>">
                    <h4>
                        <a href="<?php echo $article->getURL();?>" id="title">
                            <?php echo $article->getTitle();?>
                        </a> <a href="<?php echo $article->getCategoryURL();?>/" class="<?php echo $article->getCategoryCat();?>">
                            <span id="category">
                                <?php echo $article->getCategoryLabel();?>
                            </span>
                        </a>
                    </h4>
                    <p>
                        <?php echo $article->getPreview(15);?>
                    </p>
                </li>

                <?php $article = new Article($sectionA->eight); ?>
                <li class="<?php echo $article->getCategoryCat();?>">
                    <h4>
                        <a href="<?php echo $article->getURL();?>" id="title">
                            <?php echo $article->getTitle();?>
                        </a> <a href="<?php echo $article->getCategoryURL();?>/" class="<?php echo $article->getCategoryCat();?>">
                            <span id="category">
                                <?php echo $article->getCategoryLabel();?>
                            </span>
                        </a>
                    </h4>
                    <p>
                        <?php echo $article->getPreview(15);?>
                    </p>
                </li>
            </ul>
        </div>
        <!-- End of news list -->

        <!-- Featured articles -->        
        <div class="grid_8 alpha omega" id="featuredarticles">
            <h3>Featured Articles</h3>
            <?php 
                // Featured articles
                $sql = "SELECT `1` as one,
                    `2` as two,
                    `3` as three 
                    FROM `frontpage` 
                    WHERE layout='1' 
                    AND section='featured'";
                $featured = $db->get_row($sql);
            ?>

            <!-- Main featured article -->
            <?php $article = new Article($featured->one); ?>
            <a href="<?php echo $article->getURL(); ?>">
                <div id="imgcont">
                    <img alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(290, 190);?>" width="290px">
                </div>
                <h4>
                    <?php echo $article->getTitle();?>
                </h4>
            </a>
            <br/>
            <span><?php echo $article->getShortDesc(175); ?></span>
            <ul>
                <li>
                    Other Articles:
                </li>
                <li>
                    <?php $article = new Article($featured->two); ?>
                    <a href="<?php echo $article->getURL(); ?>">
                        <?php echo $article->getTitle();?>
                    </a>
                </li>
                <li>
                    <?php $article = new Article($featured->three); ?>
                    <a href="<?php echo $article->getURL(); ?>">
                        <?php echo $article->getTitle();?>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End of featured articles -->
        
        <?php $timing->log('end of frontpage articles'); ?>

        <!-- Editorial -->
        <div class="grid_4 alpha commentBox">
            <div class="border">
                <h4>Editorial</h4>
                    <?php
                        $sql = "SELECT id FROM `article` 
                            WHERE author='felix' 
                            AND category='2' 
                            AND text1 IS NOT NULL 
                            ORDER BY date DESC 
                            LIMIT 1";
                        $editorial = new Article($db->get_var($sql));
                    ?>
                    <h3>
                        <a href="<?php echo $editorial->getURL(); ?>">
                            <?php echo $editorial->getTitle();?>
                        </a>
                    </h3>
                    <p>
                        <?php echo $editorial->getPreview(245); ?> ...
                    </p>
            </div>
        </div>
        <!-- End of editorial -->

        <?php $timing->log('end of editorial'); ?>

        <div class="grid_4 omega">
            <div class="twitterbox">
                <h4>Twitter</h4>
                <div id="twitheader">
                    <a href="http://twitter.com/feliximperial" title="Felix Imperial"><img src="img/felixtwitter.jpg" width="50px" id="felixTwitterlogo"/></a>
                    <h5>Felix Imperial</h5>
                    <p><a href="http://twitter.com/feliximperial" target="_blank" title="Felix Twitter account">@feliximperial</a> - South Kensington</p>
                    <div class="clear"></div>
                </div>
                <ul id="felixtwitterlist">
                    <li>Loading....</li>
                </ul>
            </div>

            <?php $timing->log('end of twitter'); ?>

            <div id="weather">
                <h4>Weather <span>in South Kensington</span></h4>
                <?php
                    $requestAddress = "http://www.google.com/ig/api?weather=SW72BB&hl=en";
                    // Downloads weather data based on location - I used my zip code.
                    $xml_str = file_get_contents($requestAddress,0);
                    // Parses XML
                    $xml = new SimplexmlElement($xml_str);

                    foreach($xml->weather as $item) { ?>
                        <!-- Current conditions -->
                        <div id="current">
                            <img src="http://www.google.com<?php echo $item->current_conditions->icon['data'];?>" title="<?php echo $item->current_conditions->condition['data'];?>"/>
                            <p><b>Current</b></p>
                            <p id="temp"><?php echo $item->current_conditions->temp_c['data'];?>&#176;C</p>
                        </div>

                    <?php
                        foreach($item->forecast_conditions as $new) { ?>
                            <div class="weatherIcon">
                                <img src="http://www.google.com<?php echo $new->icon['data']; ?>" title="<?php echo $new->condition['data'];?>"/><br/>
                                <p><?php echo $new->day_of_week['data'];?></p>
                            <?php
                                $low = intval(($new->low['data'] - 32) / 1.8);
                                $high = intval(($new->high['data'] - 32) / 1.8);
                            ?>
                                <p id="temp"><?php echo $high;?>&#176;C | <?php echo $low; ?>&#176;C</p>
                            </div>
                    <?php }
                    }
                ?>
                <div class="clear"></div>
            </div>

            <?php $timing->log('end of weather'); ?>

            <div id="felixinfo">
                <h3>About Us</h3>
                <p>Felix is the award winning student newspaper of Imperial College London since 1949. Bringing you the best of news and commentary every week.</p>
                <p>If you would like to get involved or ask us a question then feel free to <a href="contact/">contact us</a></p>
            </div>
        </div>
    </div>
    <!-- End of front page articles -->
    <?php $timing->log('end of frontpage'); ?>
</div>

<!-- Featured bar -->
<div class="container_12 clearfix">
    <?php
    $sql = "SELECT id,cat,label,top_slider_1 as top FROM `category` 
            WHERE active=1 
            AND hidden=0
            AND id>0 
            ORDER BY id ASC";
    $cats = $db->get_results($sql);
    foreach($cats as $key => $cat) {
        $article = new Article($cat->top);
    ?>
        <div class="grid_3 featuredBar <?php if (($key+1) % 4 == 0) echo 'last';?>">
            <div class="border <?php echo $cat->cat;?>">
                <h3>
                    <a href="<?php echo STANDARD_URL.$cat->cat;?>/">
                        <?php echo $cat->label;?>
                    </a>
                </h3>
                <a href="<?php echo $article->getURL();?>">
                    <img id="featuredBarPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(220, 120);?>" width="220px" height="120px">
                </a>
                <h4>
                    <a href="<?php echo $article->getURL();?>">
                        <?php echo $article->getTitle();?>
                    </a>
                </h4>
                <p>
                    <?php echo $article->getPreview(10);?>
                </p>
            </div>
        </div>
    <?php } ?>
</div>
<!-- End of featured bar -->
<?php $theme->render('footer'); ?>
