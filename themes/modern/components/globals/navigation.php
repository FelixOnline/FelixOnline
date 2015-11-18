<!-- Navigation -->
<?php
	// If article page
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

      <nav class="top-bar section-bar" data-topbar role="navigation">
        <ul class="title-area">
          <li class="name">
            <a href="<?php echo STANDARD_URL; ?>"><img src="themes/modern/img/logo1.svg" class="svg" style="height: 4.8rem;"></a>
          </li>
           <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
          <li class="toggle-topbar menu-icon"><a href="#"><span><?php echo $check; ?></span></a></li>
        </ul>

        <section class="top-bar-section">
          <!-- Right Nav Section -->
          <ul class="right">
            <li class="has-form show-for-small-only">
              <form action="<?php echo STANDARD_URL; ?>search/" method="GET">
                <div class="row collapse">
                  <div class="large-8 small-9 columns">
                    <input type="text" name="q" placeholder="Find Stuff">
                  </div>
                  <div class="large-4 small-3 columns">
                    <button type="submit" class="button search-button expand alert">Search</button>
                  </div>
                </div>
              </form>
            </li>
            <li class="show-for-small-only divider"></li>
			<?php
				$cats = (new \FelixOnline\Core\CategoryManager())
					->filter('hidden = 0')
					->filter('active = 1');

				if(!$currentuser->isLoggedIn()) {
					$cats->filter('secret = 0');
				}

				$cats = $cats->filter('id > 0')
					->filter('parent IS NULL')
					->order('order', 'ASC')
					->values();

				if (!is_null($cats)) {
					foreach($cats as $key => $cat) {
						$theme->render('components/navigation/nav_item', array('item' => $cat, 'parents' => $parents, 'check' => $check)); 
					}
				}
			?>
            <li class="show-for-small-only divider"></li>
            <li class="show-for-medium-up<?php if($check == 'Search'): echo ' active'; endif; ?>">
              <a data-dropdown="search-dropdown" aria-controls="search-dropdown" data-options="align:left" aria-expanded="false" data-tooltip aria-haspopup="true" data-options="disable_for_touch:true" class="has-tip" title="Search">
                <span class="glyphicons glyphicons-search"></span>
              </a>
            </li>
            <li<?php if($check == 'Issue Archive'): echo ' class="active"'; endif; ?> >
                <a href="<?php echo STANDARD_URL.'issuearchive'; ?>" data-tooltip aria-haspopup="true" data-options="show_on:medium;disable_for_touch:true" class="has-tip" title="Issue Archive">
                <span class="glyphicons glyphicons-newspaper"></span>
                <span>
                  <span class="show-for-small-only icon-text-pad">Issue Archive</span>
                </span>
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
        </section>
      </nav>
<!-- End of Navigation -->

    <?php $theme->render('components/helpers/block_notices', array('all_pages' => !$theme->isPage('frontpage'))); ?>
