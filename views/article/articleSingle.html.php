<?php
/*
 * Article view
 */
$article = new Article($_GET['article']); // initialise new article
$article->logArticleVisit();
?>
<!-- Article wrapper -->
<div class="container_12">
    <!-- Sidebar -->
    <?php include(BASE_DIRECTORY.'/views/article/articleSidebar.html.php'); ?>
    <!-- End of sidebar -->

    <!-- Article container -->
    <div class="article grid_8 pull_4 alpha <?php echo $article->getCategoryCat();?> instapaper_body hentry">
        <!-- Article header -->
        <?php if($article->getCategoryCat() == 'comment') { ?>
            <div class="grid_5">
                <h2 class="instapaper_title entry-title">
                    <?php echo $article->getTitle(); ?>
                </h2>
                <div class="subHeader">
                    <?php echo $article->getTeaser(); ?>
                </div>
            </div>
            <div class="grid_3 alpha omega" id="commentArticlePic">
                <a href="user/<?php echo $article->getAuthor();?>/" title="<?php echo $article->getAuthor();?>">
                    <img id="articlePic" alt="<?php echo $article->getAuthor();?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_user_pic($article->getAuthor()));?>&h=160px&w=220px&zc=1">
                </a>
            </div>
        <?php } else { ?>
            <!-- Normal header -->
            <h2 class="grid_8 instapaper_title entry-title">
                <?php echo $article->getTitle(); ?>
            </h2>
            <div class="subHeader grid_8">
                <?php echo $article->getTeaserFull(); ?>
            </div>
        <?php } ?>
        <div class="articleInfo grid_8">
            <p>
                <?php echo $article->getAuthorsEnglish(); ?>
            </p>
            <p>
                <span class="<?php echo $article->getCategoryCat();?>">
                    <a href="<?php echo $article->getCategoryCat();?>/">
                        <?php echo $article->getCategoryLabel();?>
                    </a>
                </span> - <?php echo date("l F j, Y", $article->getPublishdate());?>
            </p>
            <?php
                if (is_logged_in()) {
                    $allowed = false;
                    if(check_if_section_editor($uname, $article))  // if user is editor of section article is in
                        $allowed = true;
                    else if (get_user_role($uname)==100) // if super user
                        $allowed = true;

                    if ($allowed) { ?>
                        <span id="editpage"><a href="/engine/?page=addarticle&article=<?php echo $article;?>">Edit Page</a></span>
                <?php } } 
            ?>
        </div>
        <!-- End of article header -->
        <!-- Sidebar 2 -->
            <?php include(BASE_DIRECTORY.'/views/article/articleSidebar2.html.php'); ?>
        <!-- End of sidebar 2 -->
    </div>
    <!-- End of article container -->
</div>
