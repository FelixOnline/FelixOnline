<div id="mostPopular">
	<h3>Most Popular</h3>
	<ul class="popularNav">
		<li class="selected"><a href="#mostPopRead">Read</a></li>
		<li><a href="#mostPopComment">Commented</a></li>
	</ul>
	<div class="mostPopularTab" id="mostPopRead">
		<?php 
			$viewed_articles = (new \FelixOnline\Core\ArticleManager())->getMostPopular(POPULAR_ARTICLES);
			if (!is_null($viewed_articles)) { ?>
			<ol>
				<?php foreach($viewed_articles as $article) { ?>
				<li>
					<a href="<?php echo $article->getURL(); ?>">
						<?php echo $article->getTitle(); ?>
					</a>
				</li>
				<?php } ?>
			</ol>
			<?php } else { ?>
				It doesn't look like any articles have been read recently...
			<?php } ?>
	</div>
	<div class="mostPopularTab" id="mostPopComment">
		<?php
			$commented_articles = (new \FelixOnline\Core\ArticleManager())->getMostCommented(POPULAR_ARTICLES);
			if (!is_null($commented_articles)) { ?>
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
