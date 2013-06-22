<?php
$timing->log('photo single page');

$header = array(
	'title' => 'Felix Online Media'
);

$theme->resources->addCSS(array('galleria.classic.css', 'felix-galleria.css'));
$theme->resources->addJS(array('galleria/galleria-1.2.2.js', 'galleria/themes/classic/galleria.classic.js', 'galleria/gallery.js'));

$theme->render('header', $header);
?>

<div class="container_12 media">
	<div class="grid_12 clearfix">
		<a href="<?php echo STANDARD_URL; ?>media/photo/">Back to photo albums</a>
		<h2><?php echo $media->getTitle();?></h2>
		<div class="photoSlideshow">
			<?php 
				$photos = $media->getImages();
				foreach($photos as $key => $image) { ?>
					<img src="<?php echo $image->getURL(760, 510); ?>" 
						title="<?php echo $image->getTitle();?>" 
						alt="<?php echo $image->getCaption();?>"
					/>
			<?php } ?>
		</div>
		<div class="photodesc">
			<p><?php echo $media->getDescription();?></p>
			<?php if($author = $media->getAuthor()) { ?>
				<p>By <?php echo $author;?></p>
			<?php } ?>
		</div>
		<div id="sociallinks">
			<div>
				<div id="fb-root"></div>
				<script>
					(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=200482590030408";
					fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like" data-send="true" data-width="300" data-show-faces="true" data-font="arial"></div>
			</div> 
			<div>
				<a href="https://twitter.com/share" class="twitter-share-button" data-via="feliximperial">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
		</div>
	</div>

	<!-- Sidebar -->
	<div class="sidebar grid_12">
		<h3>Most viewed albums</h3>
		<ol class="mostPhoto">
		<?php 
			$albums = $media->getMostViewed();
			foreach($albums as $album) { ?>
			<li>
				<a href="<?php echo $album->getURL(); ?>">
					<img src="<?php echo $album->getThumbnail()->getURL(210, 120); ?>"/>	
					<h5><?php echo $album->getTitle(); ?></h5>
				</a>
			</li>
		<?php } ?>
		</ol>
	</div>
	<!-- End of sidebar -->
</div>

<?php $timing->log('end of photo single page');?>
<?php $theme->render('footer'); ?>
