	<!-- Article wrapper -->
	<div class="container_12 media">
	
		<?php if ($_GET['media'] != 'true') {
			include_once('mediacont/'.$_GET['media'].'.php');
		} else { ?>
		
		<!--<h2 class="grid_12">Felix Media</h2>-->
		
		<h2 class="grid_12">Photo Gallery 
			<div id="photosoc">
				In association with <a href="http://www.union.ic.ac.uk/media/photosoc" target="_BLANK">ICU Photosoc</a>
			</div>
		</h2>
		<div class="clear"></div>
		<?php 
			global $cid;
			$sql = "SELECT * FROM media_photo_albums WHERE visible='1'";
			$result = mysql_query($sql) or die(mysql_error());
			$numalbums = mysql_num_rows($result);
			
			// Get most recent albums
			$sql = "SELECT * FROM media_photo_albums WHERE visible='1' ORDER BY albumDate DESC LIMIT 0, ".NUMBER_OF_ALBUMS_FRONT_PAGE;
			$result = mysql_query($sql) or die(mysql_error());

		?>
		
		<?php
			while($row = mysql_fetch_array($result)){
				$imgsql = "SELECT imageName FROM `media_photo_images` WHERE albumID=".$row['id']." AND id=".$row['albumThumb'];
				$thumbnail = mysql_result(mysql_query($imgsql,$cid),0); ?>
	
				<div class="grid_3 photocont mosaic-block circle">
					<a href="media/photo/<?=$row['id']?>/<?=urlise_text($row['albumName'])?>" class="mosaic-overlay">&nbsp;</a>
					<div class="mosaic-backdrop">
						<img src="/gallery/gallery_images/timthumb.php?src=/gallery/gallery_images/images/<?=$thumbnail?>&w=220px&h=150px&zc=1">
						<h5><?=$row['albumName']?></h5>
					</div>
				</div>
				
		<?php }
		?>
		<div class="clear"></div>
		<?php if ($numalbums > NUMBER_OF_ALBUMS_FRONT_PAGE) {?>
			<div class="grid_12">
				<a href="media/photo/">View all albums</a>
			</div>
		<?php } ?>
		<h2 class="grid_12 stoictv">
			Videos
			<div id="stoic">
				In association with <a href="http://www.union.ic.ac.uk/media/stoic">Stoic TV</a>
			</div>
		</h2>
		<div class="clear"></div>
		<?php 
			global $cid;
			$sql = "SELECT * FROM media_video WHERE hidden='0'";
			$result = mysql_query($sql) or die(mysql_error());
			$numvideos = mysql_num_rows($result);
			
			// Get most recent albums
			$sql = "SELECT * FROM media_video WHERE hidden='0' ORDER BY id DESC LIMIT 0, ".NUMBER_OF_ALBUMS_FRONT_PAGE;
			$result = mysql_query($sql) or die(mysql_error());

		?>
		
		<?php
			while($row = mysql_fetch_array($result)){ ?>
	
				<div class="grid_3 videocont video-block play">
					<a href="media/video/<?=$row['id']?>/<?=urlise_text($row['title'])?>" class="mosaic-overlay">&nbsp;</a>
					<div class="mosaic-backdrop">
                        <?php if($row['site'] == 'youtube') { ?>
                            <img src="http://i.ytimg.com/vi/<?=$row['video_id']?>/0.jpg" width="210px"/>
                        <?php } else {?>
                            <img src="<?php echo $row['thumbnail'];?>" width="210px" />     
                        <?php } ?>
						<h5><?=$row['title']?></h5>
					</div>
				</div>
				
		<?php }
		?>
		<div class="clear"></div>
		<?php if ($numvideos > NUMBER_OF_ALBUMS_FRONT_PAGE) {?>
			<div class="grid_12">
				<a href="media/video/">View all videos</a>
			</div>
		<?php } ?>
		
		<h2 class="grid_12 radio">
			Radio
			<div id="radio">
				In association with <a href="http://www.icradio.com/" target="_BLANK">IC Radio</a>
			</div>
		</h2>
		<div class="clear"></div>
		<p class="grid_12" >Coming soon...</p>
		
		<?php } ?>
		
	</div>
	<!-- End of article wrapper -->
