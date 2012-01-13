<div class="grid_8 pull_4 featCont layout1">
    <?php

        $other_featured = mysql_fetch_row(mysql_query("SELECT `1`,`2`,`3`,`4` FROM `top_2col`",$cid));
        list($a1,$a2,$a3,$a4) = $other_featured;

        // Section a
        $sql = "SELECT `1`,`2`,`3`,`4`,`5`,`6`,`7`,`8` FROM `frontpage` WHERE layout='1' AND section='a'";
        $sectionA = mysql_fetch_array(mysql_query($sql,$cid));
        list($A0,$A1,$A2,$A3,$A4,$A5,$A6,$A7,$A8) = $sectionA;
        // Section b
        $sql = "SELECT `1`,`2`,`3`,`4`,`5` FROM `frontpage` WHERE layout='1' AND section='b'";
        $sectionB = array_unique(mysql_fetch_array(mysql_query($sql,$cid)));
    ?>
    <!-- Top story -->
    <div class="grid_8 alpha topstory">
        <?php // Initialise top story ($A1)
            $article = $A1;
        ?>
        <div class="border <?php echo get_article_category_cat($article);?>">
            <h2><a href="<?php echo article_url($article); ?>"><?php echo get_article_title($article);?></a></h2>
            <?php $num_comments = get_article_comments($article); ?>
            <div class="subHeader">
                <p><?php echo get_article_preview_trunc($article, 50); ?></p>
                <div id="storyMeta" class="<?php if(!$num_comments) echo 'extra'; ?>">
                    <ul class="metaList">
                        <?php if($num_comments) { ?>
                            <li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
                        <?php } ?>
                        <li id="category"><a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
                    </ul>
                </div>
            </div>
            <div id="topStoryPic">
                <a href="<?php echo article_url($article);?>">
                    <img id="topStoryPhoto" alt="<?php echo get_img_title(get_img_id($article,1));?>" src="<?php echo get_img_url(get_img_id($article, 1), 340, 220);?>" height="220px" width="340px">
                </a>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- End of top story -->

    <!-- In this issue -->
    <div class="grid_2 push_6 alpha omega thisIssue">
        <h5>In this Issue</h5>
        <?php foreach($sectionB as $i => $b) { ?>
            <div class="thisIssueCont <?php if($i == 0) echo 'top';?>">
                <a href="<?php echo article_url($b);?>">
                    <img alt="<?php echo get_img_title(get_img_id($b,1));?>" src="<?php echo get_img_url(get_img_id($b, 1), 140, 140);?>" width="140px" height="140px" class="captify" rel="caption2"/>
                    <br class="c"/>
                </a>
                <div class="caption1">
                    <a href="<?php echo article_url($b);?>">
                        <?php echo get_short_article_title($b);?>
                    </a>
                </div>
                <div id="caption2">
                    <a href="<?php echo article_url($b);?>">
                        <?php echo get_short_article_desc($b); ?>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
    <!-- End of in this issue -->

    <?php $article = $A2; ?>
    <div class="grid_6 pull_2 omega alpha featBox <?php echo get_article_category_cat($article);?>">
        <h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
        <?php $num_comments = get_article_comments($article); ?>
        <div class="subHeader">
            <p><?php echo get_article_preview_trunc($article, 20); ?></p>
            <div id="storyMeta" class="<?php if(!$num_comments) echo 'extra'; ?>">
                <ul class="metaList">
                    <?php if($num_comments) { ?>
                        <li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
                    <?php } ?>
                    <li id="category"><a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
                </ul>
            </div>
        </div>
        <div id="secondStoryPic">
            <a href="<?php echo article_url($article);?>">
                <img id="secondStoryPhoto" alt="<?php echo get_img_title(get_img_id($article,1));?>" src="<?php echo get_img_url(get_img_id($article, 1), 220, 160);?>" width="220px" height="160px">
            </a>
        </div>
    </div>

    <?php $article = $A3; ?>
    <div class="grid_6 pull_2 omega alpha featBox <?php echo get_article_category_cat($article);?>" id="last">
        <h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
        <?php $num_comments = get_article_comments($article); ?>
        <div class="subHeader">
            <p><?php echo get_article_preview_trunc($article, 20); ?></p>
            <div id="storyMeta" class="<?php if(!$num_comments) echo 'extra'; ?>">
                <ul class="metaList">
                    <?php if($num_comments) { ?>
                        <li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
                    <?php } ?>
                    <li id="category"><a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
                </ul>
            </div>
        </div>
        <div id="secondStoryPic">
            <a href="<?php echo article_url($article);?>">
                <img id="secondStoryPhoto" alt="<?php echo get_img_title(get_img_id($article,1));?>" src="<?php echo get_img_url(get_img_id($article, 1), 220, 160);?>" width="220px" height="160px" >
            </a>
        </div>
    </div>

    <div class="grid_6 pull_2 alpha omega featBox bottom">
        <!-- Category -->
        <div class="grid_3 alpha header <?php echo get_article_category_cat($A4);?>">
            <a href="<?php echo get_article_category_cat($A4);?>/" class="cat <?php echo get_article_category_cat($A4);?>"><?php echo get_article_category($A4);?></a>
            <h4><a href="<?php echo article_url($A4);?>"><?php echo get_article_title($A4);?></a></h4>
        </div>
        <div class="grid_3 omega header <?php echo get_article_category_cat($A5);?>">
            <a href="<?php echo get_article_category_cat($A5);?>/" class="cat <?php echo get_article_category_cat($A5);?>"><?php echo get_article_category($A5);?></a>
            <h4><a href="<?php echo article_url($A5);?>"><?php echo get_article_title($A5);?></a></h4>
        </div>
        <div class="clear"></div>

        <!-- Pictures -->
        <div id="thirdStoryPic" class="grid_3 alpha">
            <a href="<?php echo article_url($A4);?>"><img id="thirdStoryPhoto" alt="<?php echo get_img_title(get_img_id($A4,1));?>" src="<?php echo get_img_url(get_img_id($A4, 1), 210, 130);?>" width="210px" height="130px"></a>
        </div>
        <div id="thirdStoryPic" class="grid_3 omega">
            <a href="<?php echo article_url($A5);?>"><img id="thirdStoryPhoto" alt="<?php echo get_img_title(get_img_id($A5,1));?>" src="<?php echo get_img_url(get_img_id($A5, 1), 210, 130);?>" width="210px" height="130px"></a>
        </div>
        <div class="clear"></div>

        <!-- Teaser -->
        <p class="grid_3 alpha"><?php echo get_article_preview_trunc($A4, 25); ?></p>
        <p class="grid_3 omega"><?php echo get_article_preview_trunc($A5, 25); ?></p>
        <div class="clear"></div>

        <!-- Story Meta -->
        <?php $num_comments = get_article_comments($A4);?>
        <div id="storyMeta" class="grid_3 alpha <?php if(!$num_comments) echo 'extra';?>">
            <ul class="metaList">
                <?php if($num_comments) { ?>
                    <li id="comments"><a href="<?php echo article_url($A4);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php $num_comments = get_article_comments($A5); ?>
        <div id="storyMeta" class="grid_3 omega <?php if(!$num_comments) echo 'extra';?>">
            <ul class="metaList">
                <?php if($num_comments){ ?>
                    <li id="comments"><a href="<?php echo article_url($A5);?>#commentHeader"><?php echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="clear"></div>
    </div>

    <div class="grid_6 pull_2 alpha omega newsList">
        <ul>
            <?php $article = $A6; ?>
            <li class="<?php echo get_article_category_cat($article);?>">
                <h4><a href="<?php echo article_url($article);?>" id="title"><?php echo get_article_title($article);?></a> <a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><span id="category"><?php echo get_article_category($article);?></a></span></h4>
                <p><?php echo get_article_preview_trunc($article, 15);?></p>
            </li>

            <?php $article = $A7; ?>
            <li class="<?php echo get_article_category_cat($article);?>">
                <h4><a href="<?php echo article_url($article);?>" id="title"><?php echo get_article_title($article);?></a> <a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><span id="category"><?php echo get_article_category($article);?></a></span></h4>
                <p><?php echo get_article_preview_trunc($article, 15);?></p>
            </li>

            <?php $article = $A8; ?>
            <li class="<?php echo get_article_category_cat($article);?>">
                <h4><a href="<?php echo article_url($article);?>" id="title"><?php echo get_article_title($article);?></a> <a href="<?php echo get_article_category_cat($article);?>/" class="<?php echo get_article_category_cat($article);?>"><span id="category"><?php echo get_article_category($article);?></a></span></h4>
                <p><?php echo get_article_preview_trunc($article, 15);?></p>
            </li>
        </ul>
    </div>

    <div class="grid_8 alpha omega" id="featuredarticles">
        <h3>Featured Articles</h3>
        <?php $article = 791;
            // Featured articles
            $sql = "SELECT `1`,`2`,`3` FROM `frontpage` WHERE layout='1' AND section='featured'";
            $featured = mysql_fetch_array(mysql_query($sql,$cid));
            list($F0,$F1,$F2,$F3) = $featured;
        ?>
        <a href="<?php echo article_url($F1); ?>">
            <div id="imgcont">
                <img alt="<?php echo get_img_title(get_img_id($F1,1));?>" src="<?php echo get_img_url(get_img_id($F1, 1), 290, 190);?>" width="290px">
            </div>
            <h4><?php echo get_article_title($F1);?></h4>
        </a>
        <br/><span><?php echo get_article_teaser($F1); ?></span>
        <ul>
            <li>
                Other Articles:
            </li>
            <li>
                <a href="<?php echo article_url($F2); ?>"><?php echo get_article_title($F2);?></a>
            </li>
            <li>
                <a href="<?php echo article_url($F3); ?>"><?php echo get_article_title($F3);?></a>
            </li>
        </ul>
    </div>

    <div class="grid_4 alpha commentBox">
        <div class="border">
            <h4>Editorial</h4>
                <?php

                    $sql = "SELECT * FROM `article` WHERE author='felix' AND category='2' AND text1 IS NOT NULL ORDER BY date DESC LIMIT 1";
                    $result = mysql_query($sql);
                    $row = mysql_fetch_array($result);
                ?>
                <h3><a href="<?php echo article_url($row['id']); ?>"><?php echo get_article_title($row['id']);?></a></h3>
                <p><?php echo trunc_text(clean_content2(get_article_text($row['id'])), 245); ?> ...</p>
                <span><a href="<?php echo article_url($row['id']);?>" title="Read more" id="readmorelink">Read more</a></span>
        </div>
    </div>

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

        <div id="felixinfo">
            <h3>About Us</h3>
            <p>Felix is the award winning student newspaper of Imperial College London since 1949. Bringing you the best of news and commentary every week.</p>
            <p>If you would like to get involved or ask us a question then feel free to <a href="contact/">contact us</a></p>
        </div>
    </div>

</div>
