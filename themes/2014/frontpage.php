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
					<?php $featured = $frontpage->getSection('featured'); ?>
					<?php
						foreach($featured as $article) {
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
					<h2>latest news</h2>
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
							'article' => $article
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
				<div class="felix-item-title felix-item-title-featured">
					<h2>in Felix this week</h2>
				</div>

				<?php $featured = $frontpage->getSection('b'); ?>
				<?php
					foreach($featured as $article) {
						$theme->render('components/articlelist/frontpage_box', array(
							'article' => $article
						));
					}
				?>
				<center><a class="button" href="<?php echo STANDARD_URL; ?>issuearchive/">Read more online</a></center>

				<?php
					$theme->render('sidebar/fbLikeBox');

					$theme->render('sidebar/contributionPolicy');

					$theme->render('sidebar/twitter');
				?>
			</div>
		</div>

	<!-- End of front page articles -->
	<?php $timing->log('end of frontpage'); ?>


<!-- End of featured bar -->
<?php $theme->render('components/footer'); ?>
