<?php
global $timing;

$timing->log('media page');

$header = array(
    'title' => 'Felix Online Media'
);

$theme->render('header', $header);
?>
<div class="container_12 media">
    <div id="photo" class="clearfix">
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

    <div id="video" class="clearfix">
        <h2 class="grid_12 stoictv clearfix">
            Videos
            <div id="stoic">
                In association with <a href="http://www.union.ic.ac.uk/media/stoic">Stoic TV</a>
            </div>
        </h2>
        <?php
            $videos = $media->getVideos(NUMBER_OF_ALBUMS_FRONT_PAGE);
            foreach($videos as $key => $video) { ?>
            <div class="grid_3 videocont video-block play">
                <a href="<?php echo $video->getURL(); ?>" class="mosaic-overlay">&nbsp;</a>
                <div class="mosaic-backdrop">
				    <img src="<?php echo $video->getThumbnail(); ?>" width="210">
					<h5><?php echo $video->getTitle(); ?></h5>
                </div>
            </div>
        <?php } ?>
        <div class="grid_12 clearfix">
        	<a href="<?php echo STANDARD_URL; ?>media/video/">View more videos</a>
        </div>
    </div>
    
    <div id="radio" class="clearfix">
        <h2 class="grid_12 radio clearfix">
            Radio
            <div id="radio">
                In association with <a href="http://www.icradio.com/" target="_BLANK">IC Radio</a>
            </div>
            <div id="listenlive">
                <div id="instructions">Listen Live:</div>
                <audio id="listenlive" controls preload="auto" autobuffer>
                    <source src="http://icecast.icradio.com:8000/vorbis-extra-high" />
                    <source src="http://icecast.icradio.com:8000/mp3-high" />
                    <p><a href="http://www.icradio.com/live">on the ICRadio website</a></p>  
                </audio>
            </div>
        </h2>
        <?php 
            // cache
            $cache = new Cache('icradio');
            $cache->setExpiry(6*60*60); // set expiry to 6 hours
            if($cache->start()) {
                $shows = $media->getRadioShows();
                foreach($shows as $show) { ?>
                    <div class="grid_3 radiocont">
                        <a href="<?php echo $show['link']; ?>">
                            <h5><?php echo $show['title']; ?></h5>
                        </a>
                        <p class="dj"><?php echo $show['dj']; ?></p>
                        <?php if($show['genre']) { ?>
                        <p class="genre"><?php echo $show['genre']; ?></p>
                        <?php } ?>
                    </div>
            <?php
                } 
            } $cache->stop(); ?>
    </div>
</div>
<?php $timing->log('end of media page');?>
<?php $theme->render('footer'); ?>
