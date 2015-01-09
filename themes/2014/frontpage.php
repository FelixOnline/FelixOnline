<?php
$timing->log('frontpage');

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header);
$timing->log('after header');

?>
		<div class="row felix-pad-top">
			<div class="medium-8 columns">
				<div class="felix-featured-slider">
					<?php
						foreach($spinner as $article) {
							$theme->render('components/articlelist/featured_spinner', array(
								'article' => $article
							));
						}
					?>
				</div>
			</div>
			<div class="medium-4 columns show-for-medium-up">
				<?php $theme->render('sidebar/mostPopular'); ?>
			</div>
		</div>

		<div class="row">
			<div class="medium-8 small-12 columns">
				<div class="felix-item-title felix-item-title-news">
					<h2>Latest News</h2>
				</div>

				<?php
					$articles = (new \FelixOnline\Core\ArticleManager())
					->filter('published < NOW()')
					->filter('category = %i', array(NEWS_CATEGORY_ID))
					->order('published', 'DESC')
					->limit(0, 12)
					->values();

					foreach($articles as $key => $article) {
						if($key >= 8) { continue; }
						$theme->render('components/articlelist/article_medium', array(
							'article' => $article,
							'show_authors' => true
						));
					}
				?>
				<div class="row">
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/article_small', array(
								'article' => $articles[8]
							));
						?>
					</div>
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/article_small', array(
								'article' => $articles[9]
							));
						?>
					</div>
				</div>
				<div class="row">
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/article_small', array(
								'article' => $articles[10]
							));
						?>
					</div>
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/article_small', array(
								'article' => $articles[11]
							));
						?>
					</div>
				</div>
			</div>
			<div class="medium-4 small-12 columns">
				<?php $theme->render('sidebar/fbLikeBox', array("well" => false)); ?>
				<br><br>
				<a class="button expand" href="<?php echo STANDARD_URL; ?>issuearchive/">Download a <i>Felix</i> PDF</a>
				<br>
				<a class="button expand" href="<?php echo STANDARD_URL; ?>contribute/">Contribute to <i>Felix</i></a>

				<div class="felix-item-title felix-item-title-featured">
					<h2>In <i>Felix</i> This Week</h2>
				</div>

				<?php
					foreach($thisweek as $article) {
						$theme->render('components/articlelist/frontpage_box', array(
							'article' => $article
						));
					}
				?>
				<div class="show-for-small-only"><center><a class="button" href="<?php echo STANDARD_URL; ?>issuearchive/">Read more online</a></center></div>

				<?php
				?>
					<div class="show-for-small-only"><?php $theme->render('sidebar/fbLikeBox', array("well" => true)); ?></div>
				<?php

					$theme->render('sidebar/contributionPolicy');

					$theme->render('sidebar/twitter');
				?>
			</div>
		</div>

	<!-- End of front page articles -->
	<?php $timing->log('end of frontpage'); ?>


<!-- End of featured bar -->
<?php $theme->render('components/footer'); ?>
