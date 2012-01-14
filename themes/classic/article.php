<?php
$timing->log('article page');

$article = new Article($data['id']); // todo

$theme->appendData(array('article' => $article));

$header = array(
    'title' => $article->getTitle().' - '.$article->getCategoryLabel().' - '.'Felix Online',
    'meta' => '<meta property="og:image" content="'.$article->getImage()->getURL(100).'"/>
    <meta property="og:title" content="'.$article->getTitle().' - '.$article->getCategoryLabel().' - Felix Online"/>
    <meta property="og:url" content="'.$article->getURL().'"/>
    <meta property="og:type" content="article"/>
    <meta property="og:description" content="'.$article->getTeaser().'"/>'
);

include(THEME_DIRECTORY.'/header.php'); // replace this with function
?>
<!-- Article wrapper -->
<div class="container_12">
    <!-- Sidebar -->
    <div class="sidebar grid_4 push_8">
        <?php
            $theme->render('sidebar/featuredBox');
            $theme->render('sidebar/socialLinks');
            $theme->render('sidebar/mostPopular');
            $theme->render('sidebar/fbActivity');
            //include_once(THEME_DIRECTORY.'/sidebar/mediaBox.php');
        ?>
    </div>
    <!-- End of sidebar -->

    <div class="article grid_8 pull_4 alpha <?php echo $article->getCategoryCat();?> instapaper_body hentry">
        <!-- Article header -->
        <h2 class="grid_8 instapaper_title entry-title">
            <?php echo $article->getTitle(); ?>
        </h2>
        <div class="subHeader grid_8">
            <?php echo $article->getTeaser(); ?>
        </div>
        <div class="articleInfo grid_8">
            <p>
                <?php //echo output_in_english_authors(get_article_authors_uname($article)); ?>
            </p>
            <p>
                <span class="<?php echo $article->getCategoryCat();?>">
                    <a href="<?php echo $article->getCategoryCat();?>/">
                        <?php echo $article->getCategoryLabel();?>
                    </a>
                </span> - <?php echo date("l F j, Y", $article->getDate());?>
            </p>
            <?php
                if ($currentuser->isLoggedIn()) {
                    /*
                    $allowed = false;
                    if(check_if_section_editor($uname, $article))  // if user is editor of section article is in
                        $allowed = true;
                    else if (get_user_role($uname)==100) // if super user
                        $allowed = true;

                    if ($allowed) { ?>
                <span id="editpage"><a href="/engine/?page=addarticle&article=<?php echo $article;?>">Edit Page</a></span>
            <?php	} */
                }
            ?>
        </div>
        <!-- End of article header -->
    </div>
</div>
<?php include(THEME_DIRECTORY.'/footer.php'); ?>
