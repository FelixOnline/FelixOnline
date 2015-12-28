<?php
	if(!Utility::isBot()) {
		$advert = \FelixOnline\Core\Advert::randomPick('frontpage', $sidebar, null);

		if($advert) {
			$class = 'external-img-side';

			$advert->viewAdvert();

			?>
			<div class="text-center external-img">
				<div class="<?php echo $class; ?>">
					<a href="<?php echo $advert->getUrl(); ?>" target="_blank"><img src="<?php echo $advert->getImage()->getUrl(); ?>"></a>
				</div>
			</div>
			<?php
		} else {
?>
            <div class="contact-info-box info-box">
              <h1>Get involved</h1>
              <p>It's easy to get involved with Felix, and everyone is welcome.</p>
              <div class="text-center"><button class="button small radius">Find out how</button></div>
            </div>
<?php
		}
	}
?>