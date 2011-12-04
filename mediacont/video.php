
	<?php 
		if(!isset($_GET['name'])) { ?>
			<div class="grid_12">
				<h2>Videos</h2>
			</div>
			<div class="clear"></div>
			<?php 
				global $cid;
				$sql = "SELECT * FROM media_video WHERE hidden='0'";
				$result = mysql_query($sql) or die(mysql_error());
				$numalbums = mysql_num_rows($result);
				
				// Get all albums
				$sql = "SELECT * FROM media_video WHERE hidden='0' ORDER BY id DESC LIMIT 0, ".NUMBER_OF_ALBUMS_PER_FULL_PAGE;
				$result = mysql_query($sql) or die(mysql_error());
			?>
		
		<?php
			while($row = mysql_fetch_array($result)){ ?>
				
				<div class="grid_3 videocont video-block play">
					<a href="media/video/<?=$row['id']?>/<?=urlise_text($row['title'])?>" class="mosaic-overlay">&nbsp;</a>
					<div class="mosaic-backdrop">
						<img src="http://i.ytimg.com/vi/<?=$row['video_id']?>/0.jpg" width="210px"/>
						<h5><?=$row['title']?></h5>
					</div>
				</div>
				
		<?php }
		?>
		<div class="clear"></div>
		
		<?php if ($numalbums > NUMBER_OF_ALBUMS_PER_FULL_PAGE) {?>
			<!-- Next page -->
		<?php } ?>
	<?php } else {
		hit_video($_GET['name']);
	?>
		<!-- Sidebar -->
		<div class="sidebar grid_4 push_8">
			<h3>Most viewed videos</h3>
			<ol class="mostVideo">
			<?php
				$sql = "SELECT * FROM `media_video` WHERE hidden='0' ORDER BY hits DESC LIMIT 0, 3";
				$result = mysql_query($sql) or die(mysql_error());
				while($row = mysql_fetch_array($result)){
			?>
				<li>
					<h5>
						<a href="media/video/<?php echo $row['id']; ?>/<?php echo urlise_text($row['title']);?>">
						    <?php echo $row['title']; ?>
                            <div class="mostVideoPic">
                                <?php if($row['site'] == 'youtube') { ?>
                                    <img src="http://i.ytimg.com/vi/<?=$row['video_id']?>/0.jpg" width="150px"/>
                                <?php } else {?>
                                    <img src="<?php echo $row['thumbnail'];?>" width="150px" />     
                                <?php } ?>
                            </div>
						</a>
					</h5>
					<div class="clear"></div>
				</li>
			<?php } ?>
			</ol>
			<div id="sociallinks">
				<div>
					<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo htmlentities('http://felixonline.co.uk/media/video/'.$_GET['name'].'/'.urlise_text(get_video_name($_GET['name']))); ?>&amp;layout=standard&amp;show_faces=false&amp;width=140&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=25" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:240px; height:25px;" allowTransparency="true"></iframe>
				</div>
				<div>
					<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="feliximperial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				</div>
			</div>
		</div>
		<!-- End of sidebar -->
		
		<div class="grid_8 pull_4 alpha">
			<!-- Embedded media -->
			<div class="grid_8">
				<a href="http://felixonline.co.uk/media/video/">Back to all videos</a>
				<h2><?php echo get_video_name($_GET['name'])?></h2>
				<div class="videoMedia">
                <?php if(get_video_site($_GET['name']) == 'youtube') { ?>
					<iframe title="YouTube video player" class="youtube-player" type="text/html" width="620" height="378" src="http://www.youtube.com/embed/<?php echo get_video_id($_GET['name']);?>" frameborder="0"></iframe>
                <?php } else if(get_video_site($_GET['name']) == 'vimeo') { ?>
                    <iframe src="http://player.vimeo.com/video/<?php echo get_video_id($_GET['name']); ?>" width="620" height="378" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                <?php } ?>
				</div>
				<div class="mediaDesc">
					<div class="videoMeta">
						<p><?php echo date('d F Y', strtotime(get_video_date($_GET['name'])));?></p>
					</div>
					<p><?php echo get_video_desc($_GET['name']);?></p>
				</div>
			</div>
			<!-- End of embedded media -->
			
			<!-- Related media -->
			<!--<div class="grid_8">
				<h3>Related Media</h3>
			</div>-->
			<!-- End of related media -->
			
			<!-- Comments -->
			<!--<div class="grid_8 comments">
				<h3>Comments</h3>
			</div>-->
			<!-- End of comments -->
		</div>
	<?php } ?>
