<?php
$timing->log('media page');

$header = array(
    'title' => 'Felix Online Media'
);

$theme->render('header', $header);
?>
<div class="container_12 media">
    <div id="photo">
        <h2 class="grid_12 photosoc clearfix">
            Photo Gallery 
            <div id="photosoc">
                In association with <a href="http://www.union.ic.ac.uk/media/photosoc" target="_BLANK">ICU Photosoc</a>
            </div>
        </h2>
        <?php
            $photos = $media->getPhotos(NUMBER_OF_ALBUMS_FRONT_PAGE);
            foreach($photos as $key => $id) {
                $photo = new MediaPhoto($id); ?>
            <div class="grid_3 photocont mosaic-block circle">
                <a href="<?php $photo->getURL(); ?>" class="mosaic-overlay">&nbsp;</a>
                <div class="mosaic-backdrop">
				    <img src="/gallery/gallery_images/timthumb.php?src=/gallery/gallery_images/images/<?=$thumbnail?>&w=220px&h=150px&zc=1">
					<h5><?php echo $photo->getTitle(); ?></h5>
                </div>
            </div>
        <?php } ?>
    </div>

    <div id="video">
        <h2 class="grid_12 stoictv clearfix">
            Videos
            <div id="stoic">
                In association with <a href="http://www.union.ic.ac.uk/media/stoic">Stoic TV</a>
            </div>
        </h2>
    </div>
</div>
<?php $timing->log('end of media page');?>
<?php $theme->render('footer'); ?>
