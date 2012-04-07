<?php
$timing->log('video single page');

$header = array(
    'title' => 'Felix Online Media'
);

$theme->render('header', $header);
?>

<div class="container_12 media">
    <!-- Sidebar -->
    <div class="sidebar grid_4 push_8">
        <h3>Most viewed videos</h3>
        <ol class="mostVideo">
            <?php
                $videos = $media->getMostViewed();
                foreach($videos as $video) {
            ?>
                <li>
                    <h5>
                        <a href="<?php echo $video->getURL(); ?>">
                            <?php echo $video->getTitle(); ?>
                            <div class="mostVideoPic">
                                <img src="<?php echo $video->getThumbnail(); ?>" width="150px" />     
                            </div>
                        </a>
                    </h5>
                    <div class="clear"></div>
                </li>
            <?php } ?>
        </ol>
        <div id="sociallinks">
            <div>
                <script>
                    (function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=200482590030408";
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));
                </script>
                <div class="fb-like" data-send="true" data-width="240" data-show-faces="true" data-font="arial"></div>
            </div>
            <div>
                <a href="https://twitter.com/share" class="twitter-share-button" data-via="feliximperial">Tweet</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
        </div>
    </div>
    <!-- End of sidebar -->
    
    <div class="grid_8 pull_4 alpha">
        <!-- Embedded media -->
        <div class="grid_8">
            <a href="<?php echo STANDARD_URL; ?>media/video/">Back to all videos</a>
            <h2><?php echo $media->getTitle(); ?></h2>
            <div class="videoMedia">
                <?php echo $media->getEmbed(); ?>
            </div>
            <div class="mediaDesc">
                <div class="videoMeta">
                    <p><?php echo date('d F Y', $media->getDate());?></p>
                </div>
                <p><?php echo $media->getDescription(); ?></p>
            </div>
        </div>
        <!-- End of embedded media -->
    </div>
</div>

<?php $timing->log('end of photo single page');?>
<?php $theme->render('footer'); ?>
