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
</div>
<?php
$timing->log('end of category page');
$theme->render('footer');
?>
