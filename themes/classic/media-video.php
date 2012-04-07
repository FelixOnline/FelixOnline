<?php
$timing->log('videos page');

$header = array(
    'title' => 'Felix Online Media'
);

$theme->render('header', $header);
?>
<div class="container_12 media">
    <div class="grid_12 clearfix">
        <h2>Videos</h2>
    </div>
    <div class="clearfix">
        <?php 
        $videos = $media->getVideos();
        foreach($videos as $video) { ?>
            <div class="grid_3 videocont video-block play">
            <a href="<?php echo $video->getURL(); ?>" class="mosaic-overlay">&nbsp;</a>
                <div class="mosaic-backdrop">
                    <img src="<?php echo $video->getThumbnail(); ?>" width="210">
                    <h5><?php echo $video->getTitle(); ?></h5>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php $timing->log('end of videos page');?>
<?php $theme->render('footer'); ?>
