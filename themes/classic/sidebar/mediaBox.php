<?php $media = new Media(); ?>
<div id="mediaBox">
	<h3>Media Box</h3>
	<ul class="mediaBoxNav">
		<li><a href="#mediaPhoto">Photo</a></li>
		<li class="selected"><a href="#mediaVideo">Video</a></li>
		<li><a href="#mediaRadio">Radio</a></li>
	</ul>
	<div class="mediaBoxTab" id="mediaPhoto">
		<?php 
			$albums = $media->getAlbums(1);
			foreach($albums as $album) {
		?>
		<a href="<?php echo $album->getURL(); ?>">
			<img src="<?php echo $album->getThumbnail()->getURL(258, 160);?>"/>
			<p><?php echo $album->getTitle();?></p>
		</a>
		<?php } ?>
	</div>
	<div class="mediaBoxTab" id="mediaVideo">
		<?php 
			$videos = $media->getVideos(1);
			foreach($videos as $video) {
		?>
		<a href="<?php echo $video->getURL(); ?>">
			<img src="<?php echo $video->getThumbnail(); ?>" width="258px"/>
			<p><?php echo $video->getTitle();?></p>
		</a>
		<?php } ?>
	</div>
	<div class="mediaBoxTab" id="mediaRadio">
		<p>Listen Live:</p>
		<audio id="listenlive" controls preload="none">
			<source src="http://icecast.icradio.com:8000/mp3-high" type="audio/mpeg" />
			<source src="http://icecast.icradio.com:8000/vorbis-low" type="audio/ogg; codecs=vorbis" />
			<p><a href="http://www.icradio.com/live">on the ICRadio website</a></p>  
		</audio>
		<?php if (ICRADIO) { ?>
			<ul id="radiolist">
			<?php
				// cache
				$cache = new Cache('sidebar-icradio');
				$cache->setExpiry(6*60*60); // set expiry to 6 hours
				if($cache->start()) {
					$shows = $media->getRadioShows();
					foreach($shows as $show) { ?>
					<li>
						<a href="<?php echo $show['link']; ?>"> 
							<?php echo $show['title']; ?>
						</a>
					</li>
				<?php } 
				} $cache->stop(); ?>
			</ul>
		<?php } ?>
	</div>
</div>
<?php $timing->log('after mediabox'); ?>
