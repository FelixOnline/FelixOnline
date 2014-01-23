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

			$cats = Category::getCategories();

			if (!is_null($cats)) {
				foreach($cats as $key => $cat) { ?>
					<li class="<?php echo $cat->cat; ?> <?php if(isset($check) && $check == $cat->cat) echo 'selected'; ?> <?php if($cat->cat == 'news') echo 'first'; ?> <?php if($cat->cat == 'sport') echo 'last'; ?>">
						<a href="<?php echo STANDARD_URL.$cat->cat; ?>/">
							<?php echo $cat->label; ?>
						</a>
					</li>
			<?php }
			} ?>
		</ul>
	</div>
</div>
<!-- End of Navigation -->
