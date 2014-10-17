<div id="mostPopular">
	<div class="felix-item-title felix-item-title felix-item-title-generic">
		<h3>recent activity</h3>
	</div>
	<dl class="tabs" data-tab>
		<dd class="active"><a href="#most-read">Read</a></dd>
		<dd><a href="#most-commented">Commented</a></dd>
	</dl>
	<div class="tabs-content">
		<div class="content active recent-items-content" id="most-read">
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
		<div class="content recent-items-content" id="most-commented">
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
</div>
