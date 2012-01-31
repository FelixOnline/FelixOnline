<?php
$timing->log('category page');

$header = array(
    'title' => $category->getLabel().' - '.'Felix Online',
    'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header);
?>
<!-- Section header -->
<div class="container_12 clearfix">
    <div class="grid_12 section_header <?php echo $category->getCat(); ?>">
        <h2><?php echo $category->getLabel(); ?></h2>
        <div id="info">
            <ul>
                <li class="editors">Editors: <b><?php echo Utility::outputUserList($category->getEditors(), true);?></b></li>
                <?php if($category->getEmail()) { ?>
                    <li class="email"><?php echo Utility::hideEmail($category->getEmail());?></li>
                <?php } ?>
                <li class="rss"><a href="rss.php?cat=<?php echo $category->getCat();?>" target="_blank">RSS Feed</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- End of section header -->

<!-- Section articles -->
<div class="container_12 section">
    <!-- Sidebar -->
    <div class="sidebar grid_4 push_8">
        <?php 
            if($category->getTwitter()) { 
                $theme->render('sidebar/categoryTwitter');
            }
            $theme->render('sidebar/categoryFeaturedBox');
            //include_once('sidebar/mediaBox.php');
            $theme->render('sidebar/socialLinks');
            $theme->render('sidebar/fbActivity');
            $theme->render('sidebar/mostPopular');
        ?>
    </div>
    <!-- End of sidebar -->

    <!-- Category articles -->
    <div class="grid_8 pull_4">
        <?php
            /* First page */
            if($pagenum == 1) { 
                $articles = $category->getArticles(1);
                foreach($articles as $key => $object) {
                    $article = new Article($object->id);
                    if($key == 1) { // top story ?>
                        <!-- Top story -->
                        <div class="topstory">
                            <div class="border clearfix">
                                <h2>
                                    <a href="<?php echo $article->getURL();?>">
                                        <?php echo $article->getTitle();?>
                                    </a>
                                </h2>
                                <div class="subHeader <?php if(!$article->getImage()) { echo "wide"; } else if($article->getImage()->isTall(300, 300)) { echo ' tallpic'; }?>">
                                    <p>
                                        <?php 
                                            if(!$article->getImage()) { 
                                                echo $article->getPreview(50);
                                            } else {
                                                echo $article->getPreview(35);
                                            }
                                        ?>
                                    </p>
                                    <div id="storyMeta">
                                        <ul class="metaList">
                                            <?php if($article->getNumComments()) { ?>
                                                <li id="comments">
                                                    <a href="<?php echo $article->getURL();?>#commentHeader">
                                                        <?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <?php echo date("l F j, Y",$article->getDate());?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php if ($image = $article->getImage()) { ?>
                                    <div id="topStoryPic">
                                        <a href="<?php echo $article->getURL();?>">
                                        <?php if($image->isTall(300, 300)) { ?>
                                            <img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(150, 180);?>">
                                        <?php } else { ?>
                                            <img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(300, 180);?>">
                                        <?php } ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- End of top story -->
            <?php   } else if ($key < 4 && $key > 1) { // middle stories ?>
                        <div class="featBox">
                            <div class="border clearfix">
                                <h3>
                                    <a href="<?php echo $article->getURL();?>">
                                        <?php echo $article->getTitle();?>
                                    </a>
                                </h3>
                                <div class="subHeader <?php if(!$article->getImage()) { echo "wide"; } else if($article->getImage()->isTall(160, 160)) { echo ' tallpic'; }?>">
                                    <p>
                                        <?php 
                                            if(!$article->getImage()) { 
                                                echo $article->getPreview(60);
                                            } else {
                                                echo $article->getPreview(35);
                                            }
                                        ?>
                                    </p>
                                    <div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra';?>">
                                        <ul class="metaList">
                                            <?php if($article->getNumComments()) { ?>
                                                <li id="comments">
                                                    <a href="<?php echo $article->getURL();?>#commentHeader">
                                                        <?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <?php echo date("l F j, Y",$article->getDate());?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php if ($image = $article->getImage()) { ?>
                                    <div id="secondStoryPic">
                                        <a href="<?php echo $article->getURL();?>">
                                        <?php if($image->isTall(220, 220)) { ?>
                                            <img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(120, 130);?>">
                                        <?php } else { ?>
                                            <img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(220, 120);?>">
                                        <?php } ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
            <?php   } else if ($key > 3) { // end stories ?>
                        <div class="featBox">
                            <div class="border clearfix">
                                <h3>
                                    <a href="<?php echo $article->getURL();?>">
                                        <?php echo $article->getTitle();?>
                                    </a>
                                </h3>
                                <div class="subHeader <?php if(!$article->getImage()) { echo "wide"; } else if($article->getImage()->isTall(160, 160)) { echo ' tallpic'; }?>">
                                    <p>
                                        <?php 
                                            if(!$article->getImage()) { 
                                                echo $article->getPreview(60);
                                            } else {
                                                echo $article->getPreview(30);
                                            }
                                        ?>
                                    </p>
                                    <div id="storyMeta">
                                        <ul class="metaList">
                                            <?php if($article->getNumComments()) { ?>
                                                <li id="comments">
                                                    <a href="<?php echo $article->getURL();?>#commentHeader">
                                                        <?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <?php echo date("l F j, Y",$article->getDate());?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php if ($image = $article->getImage()) { ?>
                                    <div id="thirdStoryPic">
                                        <a href="<?php echo $article->getURL();?>">
                                        <?php if($image->isTall(160, 160)) { ?>
                                            <img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(100, 120);?>">
                                        <?php } else { ?>
                                            <img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(160, 120);?>">
                                        <?php } ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
            <?php   }
                }
            }
            /* Not first page */
            else {
                $articles = $category->getArticles($pagenum);
                foreach($articles as $key => $object) {
                    $article = new Article($object->id); ?>
						<div class="featBox">
							<div class="border clearfix">
                                <h3>
                                    <a href="<?php echo $article->getURL();?>">
                                        <?php echo $article->getTitle();?>
                                    </a>
                                </h3>
                                <p>
                                    <?php echo $article->getPreview(30); ?>
                                </p>
								<div id="storyMeta">
									<ul class="metaList">
                                        <?php if($article->getNumComments()) { ?>
                                            <li id="comments">
                                                <a href="<?php echo $article->getURL();?>#commentHeader">
                                                    <?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <?php echo date("l F j, Y",$article->getDate());?>
                                        </li>
									</ul>
								</div>
							</div>
						</div>
                <?php }
            }
        ?>
    </div>
    <!-- End of category articles -->

    <!-- Page list -->
    <div class="grid_8 clearfix">
        <ul id="pageList" class="clearfix">
            <li id="desc">Pages:</li>
            <?php if ($pagenum != 1) { // Previous page arrow ?>
                <li class="arrow">
                    <a href="<?php echo $category->getURL($pagenum-1); ?>">
                        &#171;
                    </a>
                </li>
            <?php } 
                $pages = $category->getNumPages();
                if ($pages > 1) {
                    $span = NUMBER_OF_PAGES_IN_PAGE_LIST;
                    if ($pages > $span) { // more pages than limit
                        if ($pagenum >= ($span/2)) {
                            $start = ($pagenum - $span/2)+1;
                            $limit = $pagenum + $span/2;
                            if ($limit > $pages) {
                                $limit = $pages;
                                $start = $limit - $span;
                            }
                        } else {
                            $start = 1;
                            $limit = $span;
                        }
                    } else {
                        $limit = $pages;
                        $start = 1;
                    }
                    for ($i=$start;$i<=$limit;$i++) {
                        if($pagenum==$i) { ?>
                            <li class="selected">
                        <?php } else { ?>
                            <li>
                                <a href="<?php echo $category->getURL($i); ?>">
                        <?php } ?>
                            <?php echo $i; ?>
                        <?php if($pagenum==$i) { ?>
                            </li>
                        <?php } else { ?>
                            </a></li>
                        <?php }
                    }
                } else { ?>
                    <li class="selected">1</li>
                <?php }
                if ($pagenum != $pages) { // Next page arrow ?>
                    <li class="arrow">
                        <a href="<?php echo $category->getURL($pagenum+1);?>">
                            &#187;
                        </a>
                    </li>
                <?php }
            ?>
        </ul>
    </div>
    <!-- End of page list -->
</div>
<?php
$timing->log('end of category page');
$theme->render('footer');
?>
