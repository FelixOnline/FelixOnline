<!-- Navigation -->
<?php
	// If article page
	if ($theme->isPage('article')) {
		$check = $article->getCategory()->getCat();
	} else if ($theme->isPage('category')) { // if category page
		$check = $category->getCat();
	} else {
		$check = 'home';
	}
?>

		<div class="felix-nav">
			<div class="row">
				<div class="small-12 columns">
					<nav class="top-bar nav-<?php echo $check; ?>" data-topbar="" role="navigation">
						<ul class="title-area">
							<li class="name"><h1 class="show-for-small-only"><b><?php echo strtoupper($check); ?></b></h1></li>
							<li class="toggle-topbar"><a href=""><span>Toggle Menu</span></a></li>
						</ul>

						<section class="top-bar-section">
							<ul class="left">
								<?php
								$cats = (new \FelixOnline\Core\CategoryManager())
									->filter('hidden = 0')
									->filter('id > 0')
									->order('order', 'ASC')
									->values();

								if (!is_null($cats)) {
									foreach($cats as $key => $cat) { ?>
										<li class="nav-<?php echo $cat->getCat(); ?> <?php if(isset($check) && $check == $cat->getCat()) echo 'active'; ?>">
											<a href="<?php echo STANDARD_URL.$cat->getCat(); ?>/">
												<?php echo $cat->getLabel(); ?>
											</a>
										</li>
								<?php }
								} ?>
							</ul>
						</section>
					</nav>
				</div>
			</div>
		</div>
<!-- End of Navigation -->
