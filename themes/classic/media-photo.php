<?php
$timing->log('photo albums page');

$header = array(
    'title' => 'Felix Online Media'
);

$theme->render('header', $header);
?>
<div class="container_12 media">
    <div class="grid_12 clearfix">
        <h2>Photo Albums</h2>
    </div>
    <div class="clearfix">
        <?php 
        $albums = $media->getAlbums();
        foreach($albums as $album) { ?>
            <div class="grid_3 photocont mosaic-block circle">
            <a href="<?php echo $album->getURL(); ?>" class="mosaic-overlay">&nbsp;</a>
                <div class="mosaic-backdrop">
                    <img src="<?php echo $album->getThumbnail()->getURL(220, 150); ?>">
                    <h5><?php echo $album->getTitle(); ?></h5>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php $timing->log('end of photo albums page');?>
<?php $theme->render('footer'); ?>
