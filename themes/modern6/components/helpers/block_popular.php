<div class="trending-block info-box info-title-only">
	<h1>Trending</h1>
</div>
<div class="info-secondary-box trending-tabs">
	<ul class="tabs" data-tabs id="trending-tabs">
		<li class="tabs-title is-active"><a href="#most-read" aria-selected="true">Read</a></li>
		<li class="tabs-title"><a href="#most-commented">Commented</a></li>
	</ul>
	<div class="tabs-content" data-tabs-content="trending-tabs" style="border: none">
		<div class="tabs-panel is-active trending recent-items-content" id="most-read">
			<?php 

			$viewed_articles = (new \FelixOnline\Core\ArticleManager())->getMostPopular(\FelixOnline\Core\Settings::get('popular_articles'));
			
			if (!is_null($viewed_articles)) {

			?>
			<ol>
				<?php foreach($viewed_articles as $article) { ?>
				
				<li>
					<a href="<?php echo $article->getURL(); ?>">
						<?php echo $article->getTitle(); ?>
					</a>
				</li>

				<?php }	?>
			</ol>

			<?php } else { ?>

			It doesn't look like any articles have been read recently...

			<?php } ?>
		</div>
		<div class="tabs-panel trending recent-items-content" id="most-commented">
			<?php

			$commented_articles = (new \FelixOnline\Core\ArticleManager())->getMostCommented(\FelixOnline\Core\Settings::get('popular_articles'));

			if (!is_null($commented_articles)) {

			?>
			<ol>
				<?php foreach ($commented_articles as $article) { ?>

				<li>
					<a href="<?php echo $article->getURL(); ?>">
						<?php echo $article->getTitle(); ?>
					</a>
				</li>

				<?php } ?>
			</ol>

			<?php } else { ?>

			Nobody has posted any comments recently.

			<?php } ?>
		</div>
	</div>
</div>
