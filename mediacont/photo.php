	<?php 
		/*
			TODO:
				Comments on albums
				Facebook like's and twitter on albums
				Links back to all albums on album page
				Link to next and previous album
				Pagination on album list
				Most viewed albums
				
			Completed:
				
		*/
	?>
	
	<?php 
		if(!isset($_GET['name'])) { ?>
			<div class="grid_12">
				<h2>Photo Albums</h2>
			</div>
			<div class="clear"></div>
			<?php 
				global $cid;
				$sql = "SELECT * FROM media_photo_albums WHERE visible='1'";
				$result = mysql_query($sql) or die(mysql_error());
				$numalbums = mysql_num_rows($result);
				
				// Get all albums
				$sql = "SELECT * FROM media_photo_albums WHERE visible='1' ORDER BY albumDate DESC LIMIT 0, ".NUMBER_OF_ALBUMS_PER_FULL_PAGE;
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
		
		<?php if ($numalbums > NUMBER_OF_ALBUMS_PER_FULL_PAGE) {?>
			<!-- Next page -->
		<?php } ?>
	<?php } else {
		hit_album($_GET['name']);
	?>
		
	<div class="grid_12">
		<a href="http://felixonline.co.uk/media/photo/">Back to photo albums</a>
		<h2><?php echo get_album_name($_GET['name']);?></h2>
		<div class="photoSlideshow">
			<?php 
				$sql = "SELECT * FROM `media_photo_images` WHERE albumID='".$_GET['name']."' ORDER BY id ASC";
				$result = mysql_query($sql);
				while($row = mysql_fetch_array($result)){ ?>
					<img src="/gallery/gallery_images/timthumb.php?src=/gallery/gallery_images/images/<?php echo $row['imageName'];?>&h=510px&zc=0" title="<?=$row['imageTitle'];?>" alt="<?=$row['imageCaption'];?>"/>
			<?php }
			?>
		</div>
		<div class="photodesc">
			<p><?php echo get_album_desc($_GET['name']);?></p>
			<?php if($author = get_album_author($_GET['name'])) { ?>
			<p>By <?php echo $author;?></p>
			<?php } ?>
		</div>
		<div id="sociallinks">
			<div>
				<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo htmlentities('http://felixonline.co.uk/media/photo/'.$_GET['name'].'/'.urlise_text(get_album_name($_GET['name']))); ?>&amp;layout=standard&amp;show_faces=false&amp;width=140&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=25" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:240px; height:25px;" allowTransparency="true"></iframe>
			</div> 
			<div>
				<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="feliximperial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			</div>
		</div>
	</div>
	<div class="clear"></div>
		<!-- Sidebar -->
		<div class="sidebar grid_12">
			<h3>Most viewed albums</h3>
			<ol class="mostPhoto">
				<?php 
					$sql = "SELECT * FROM `media_photo_albums` WHERE visible='1' ORDER BY hits DESC LIMIT 0, 3";
					$result = mysql_query($sql) or die(mysql_error());
					while($row = mysql_fetch_array($result)){
						$imgsql = "SELECT imageName FROM `media_photo_images` WHERE albumID=".$row['id']." AND id=".$row['albumThumb'];
						$thumbnail = mysql_result(mysql_query($imgsql,$cid),0);
				?>
				<li>
					<a href="media/photo/<?=$row['id']?>/<?=urlise_text($row['albumName'])?>">
						<img src="/gallery/gallery_images/timthumb.php?src=/gallery/gallery_images/images/<?=$thumbnail?>&amp;w=210px&amp;h=120px&amp;zc=1"/>	
						<h5><?=$row['albumName']?></h5>
					</a>
				</li>
				<?php } ?>
			</ol>
		</div>
		<!-- End of sidebar -->
		
	<?php } ?>