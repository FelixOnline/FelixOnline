<?php
	// What are we currently viewing?
	$parents = array();
	if ($theme->isPage('article')) {
		$parents = $article->getCategory()->getAllParents();
		$check = $article->getCategory()->getCat();
	} else if ($theme->isPage('category') || $theme->isPage('category_page1')) { // if category page
		$parents = $category->getAllParents();
		$check = $category->getCat();
	} else if($theme->isSite('archive')) {
		$check = 'Issue Archive';
	} else if($theme->isPage('user')) {
		$check = $user->getName();
	} else if($theme->isPage('search')) {
		$check = 'Search';
	} else {
		$check = 'Menu';
	}
?>

<div class="top-bar section-bar" style="width: 100%">
	<div class="row">
		<div class="top-bar-title">
			<span data-responsive-toggle="main-menu" data-hide-for="medium">
				<span class="menu-icon light" data-toggle></span>
			</span>
			<strong><a href="<?php echo STANDARD_URL; ?>"><img src="<?php echo STANDARD_URL; ?>themes/modern/img/logo1.svg" class="svg" style="height: 3rem;"></a></strong>
		</div>
		<div class="top-bar-right">
			<ul class="dropdown menu" data-dropdown-menu>
				<li<?php if($check == 'Search'): echo ' class="active"'; endif; ?> >
					<a data-toggle="search-dropdown" data-tooltip aria-haspopup="true" data-options="disable_for_touch:true" class="has-tip" title="Search">
						<span class="glyphicons glyphicons-search"></span>
					</a>
				</li>
				<li<?php if($check == 'Issue Archive'): echo ' class="active"'; endif; ?> >
					<a href="<?php echo STANDARD_URL.'issuearchive'; ?>" data-tooltip aria-haspopup="true" data-options="show_on:medium;disable_for_touch:true" class="has-tip" title="Issue Archive">
						<span class="glyphicons glyphicons-newspaper"></span>
					</a>
				</li>
				<?php
					if(!$currentuser->isLoggedIn()) {
						$theme->render('components/navigation/user_loggedout', array('check' => '', 'parents' => $parents));
					} else {
						$theme->render('components/navigation/user_loggedin', array('check' => $user, 'parents' => $parents));
					}
				?>
			</ul>
		</div>
		<div class="top-bar-left">
			<div id="main-menu">
				<ul class="vertical medium-horizontal menu" data-responsive-menu="drilldown medium-dropdown">
					<?php
						$cats = (new \FelixOnline\Core\CategoryManager())
							->filter('hidden = 0')
							->filter('active = 1')
							->filter('id > 0')
							->filter('parent IS NULL')
							->order('order', 'ASC')
							->values();

						if (!is_null($cats)) {
							foreach($cats as $key => $cat) {
								$theme->render('components/navigation/nav_item', array('item' => $cat, 'parents' => $parents, 'check' => $check)); 
							}
						}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>

	