<?php
$header = array(
    'title' => 'Felix Online - The student voice of Imperial College London',
    'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

include($this->directory.'/header.php');
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
</div>
<?php include($this->directory.'/footer.php'); ?>
