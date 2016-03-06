	<?php if($large): ?>
		<div class="row small-up-12 medium-up-4">
	<?php else: ?>
		<div class="row small-up-12 medium-up-2">
	<?php endif; ?>
			<?php
				$viewed_articles = (new \FelixOnline\Core\ArticleManager())->getMostPopular(\FelixOnline\Core\Settings::get('popular_articles'));
				
				if (!is_null($viewed_articles)) {
					foreach($viewed_articles as $article) {
			?>
					<div class="columns">
			<?php
						$theme->render('components/category/block_normal', array(
							'article' => $article,
							'show_category' => true,
							'headshot' => false
						));
			?>
					</div>
			<?php
					}
				} else {
			?>

			<p>It doesn't look like any articles have been read recently...</p>

			<?php
				}
			?>
		</div>