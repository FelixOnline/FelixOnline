	<?php if($large): ?>
		<div class="row small-up-12 medium-up-4">
	<?php else: ?>
		<div class="row small-up-12 medium-up-2">
	<?php endif; ?>
			<?php
				$commented_articles = (new \FelixOnline\Core\ArticleManager())->getMostCommented(\FelixOnline\Core\Settings::get('popular_articles'));
				
				if (!is_null($commented_articles)) {
					foreach($commented_articles as $article) {
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

			<p>Nobody has posted any comments recently.</p>

			<?php
				}
			?>
		</div>