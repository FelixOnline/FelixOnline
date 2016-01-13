<?php
	if(!Utility::isBot()) {
		if(!isset($sidebar)) {
			$sidebar = false;
		}
		
		if($article) {
			$advert = \FelixOnline\Core\Advert::randomPick('articles', $sidebar, $article->getCategory());
		} elseif($category) {
			$advert = \FelixOnline\Core\Advert::randomPick('categories', $sidebar, $category);
		} else {
			$advert = \FelixOnline\Core\Advert::randomPick('frontpage', $sidebar, null);
		}

		if($advert) {
			if(!$sidebar) {
				$class = 'external-img-top advert-area';
			} else {
				$class = 'external-img-side';
			}

			$advert->viewAdvert();

			?>
			<div class="text-center external-img">
				<div class="<?php echo $class; ?>">
					<a href="<?php echo $advert->getUrl(); ?>" target="_blank"><img src="<?php echo $advert->getImage()->getUrl(); ?>"></a>
				</div>
			</div>
			<?php
		}
	}
?>