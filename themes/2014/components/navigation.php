<!-- Navigation -->
<div class="navigation container_12">
	<div class="grid_12 clearfix">
		<ul id="navbar" class="clearfix">
			<?php
			// If article page
			if ($theme->isPage('article')) {
				$check = $article->getCategory()->getCat();
			} else if ($theme->isPage('category')) { // if category page
				$check = $category->getCat();
			}

			$cats = (new \FelixOnline\Core\CategoryManager())
				->filter('hidden = 0')
				->filter('id > 0')
				->order('order', 'ASC')
				->values();

			if (!is_null($cats)) {
				foreach($cats as $key => $cat) { ?>
					<li class="<?php echo $cat->getCat(); ?> <?php if(isset($check) && $check == $cat->getCat()) echo 'selected'; ?> <?php if($key == 0) echo 'first'; ?> <?php if($key == count($cats) - 1) echo 'last'; ?>">
						<a href="<?php echo STANDARD_URL.$cat->getCat(); ?>/">
							<?php echo $cat->getLabel(); ?>
						</a>
					</li>
			<?php }
			} ?>
		</ul>
	</div>
</div>
<!-- End of Navigation -->
