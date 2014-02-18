<?php
	$category = $article->getCategory();

	$featured = array();
	foreach (array(
		'top_sidebar_1',
		'top_sidebar_2',
		'top_sidebar_3',
		'top_sidebar_4',
		'top_sidebar_5',
	) as $column) {
		$fields = $category->getFields();
		if (isset($fields[$column])) {
			$featured[$column] = new \FelixOnline\Core\Article($fields[$column]);
		}
	}	
?>
<div id="featuredBox">
	<h3>Top <span class="<?php echo $article->getCategory()->getCat();?>"><?php echo $article->getCategory()->getLabel();?></span> Stories</h3>
	<ul class="clearfix">
	<?php 
		foreach($featured as $key => $article) { 
			if ($key == 'top_sidebar_1') { ?>
				<li class="withPic">
					<a href="<?php echo $article->getURL(); ?>">
						<h5><?php echo $article->getTitle();?></h5>
						<div class="featuredPic">
							<a href="<?php echo $article->getURL(); ?>">
								<?php if ($article->getImage()): ?>
									<img id="featuredPhoto" alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(150, 100); ?>">
								<?php else: ?>
									<img id="featuredPhoto" alt="" src="<?php echo IMAGE_URL.'/150/100/'.DEFAULT_IMG_URI; ?>">
								<?php endif; ?>
							</a>
						</div>
					</a>
				</li>
			<?php } else { ?>
				<li>
					<a href="<?php echo $article->getURL(); ?>">
						<?php echo $article->getTitle();?>
					</a>
				</li>
			<?php } 
		} ?>
	</ul>
</div>
