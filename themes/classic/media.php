<?php
global $timing;

$timing->log('media page');

$header = array(
    'title' => 'Felix Online Media'
);

$theme->render('header', $header);
?>
<div class="container_12 media">
    <div id="photo">
        <h2 class="grid_12 photosoc clearfix">
            Photo Albums 
            <div id="photosoc">
                In association with <a href="http://www.union.ic.ac.uk/media/photosoc" target="_BLANK">ICU Photosoc</a>
            </div>
        </h2>
        <?php
            $albums = $media->getAlbums(NUMBER_OF_ALBUMS_FRONT_PAGE);
            foreach($albums as $key => $album) { ?>
            <div class="grid_3 photocont mosaic-block circle">
                <a href="<?php echo $album->getURL(); ?>" class="mosaic-overlay">&nbsp;</a>
                <div class="mosaic-backdrop">
				    <img src="<?php echo $album->getThumbnail()->getURL(220, 150); ?>">
					<h5><?php echo $album->getTitle(); ?></h5>
                </div>
            </div>
        <?php } ?>
        <div class="grid_12 clearfix">
        	<a href="<?php echo STANDARD_URL; ?>media/photo/">View more photo albums</a>
        </div>
    </div>

    <div id="video">
        <h2 class="grid_12 stoictv clearfix">
            Videos
            <div id="stoic">
                In association with <a href="http://www.union.ic.ac.uk/media/stoic">Stoic TV</a>
            </div>
        </h2>
        <div class="grid_12 clearfix">
        	<a href="<?php echo STANDARD_URL; ?>media/video/">View more videos</a>
        </div>
    </div>
</div>
<?php $timing->log('end of media page');?>
<?php $theme->render('footer'); ?>
