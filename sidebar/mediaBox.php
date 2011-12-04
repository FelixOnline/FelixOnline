<div id="mediaBox">
	<h3>Media Box</h3>
	<ul class="mediaBoxNav">
		<li><a href="#mediaPhoto">Photo</a></li>
		<li class="selected"><a href="#mediaVideo">Video</a></li>
		<li><a href="#mediaRadio">Radio</a></li>
	</ul>
	<div class="mediaBoxTab" id="mediaPhoto">
		<?php 
			// Get most recent album from phig 
			$sql = "SELECT * FROM `media_photo_albums` WHERE visible = 1 ORDER BY id DESC";
			$result = mysql_query($sql);
			$row = mysql_fetch_array( $result );
			
			$imgsql = "SELECT * FROM `media_photo_images` WHERE id=".$row['albumThumb'];
			$imgrow = mysql_fetch_array(mysql_query($imgsql));
		?>
		<a href="media/photo/<?=$row['id']?>/<?=urlise_text($row['albumName'])?>/">
			<img src="/gallery/gallery_images/timthumb.php?src=/gallery/gallery_images/images/<?php echo $imgrow['imageName'];?>&w=258px&h=160&zc=1"/>
			<p><?php echo $row['albumName'];?></p>
		</a>
	</div>
	<div class="mediaBoxTab" id="mediaVideo">
		<?php 
			// Get most recent album from phig 
			$sql = "SELECT * FROM `media_video` WHERE hidden = 0 ORDER BY id DESC";
			$result = mysql_query($sql);
			$row = mysql_fetch_array( $result );
		?>
		<a href="media/video/<?=$row['id']?>/<?=urlise_text($row['title'])?>/">
            <?php if($row['site'] == 'youtube') { ?>
                <img src="http://i.ytimg.com/vi/<?=$row['video_id']?>/0.jpg" width="258px"/>
            <?php } else {?>
                <img src="<?php echo $row['thumbnail'];?>" width="258px" />     
            <?php } ?>
			<p><?php echo $row['title'];?></p>
		</a>
	</div>
	<div class="mediaBoxTab" id="mediaRadio">
		<p>Coming soon...</p>
	</div>
</div>
