<?php

$header = array(
	'meta' => '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);

?>

<div class="row main-row top-row" data-equalizer="top">
	<div class="small-12 large-9 columns">
		<div class="row">
			<div class="small-12 medium-8 columns">
				<div class="carousel-block" data-equalizer="carousel">
				<?php
					foreach($spinner as $article) {
						if(!$article->getArticle()) {
							continue;
						}

						$theme->render('components/category/block_carousel', array(
							'article' => $article->getArticle()
						));
					}
				?>
				</div>
			</div>
			<div class="small-12 medium-4 columns">
				<div class="row news">
					<div class="small-12 columns">
						<div class="bar-text">Latest news</div>
					</div>
				</div>

				<div class="row">
					<?php
						$articles = (new \FelixOnline\Core\ArticleManager())
							->filter('published < NOW()')
							->filter('category = %i', array(\FelixOnline\Core\Settings::get('news_category_id')))
							->order(array('published', 'id'), 'DESC')
							->limit(0, 6)
							->values();

						if($articles) {
							foreach($articles as $article) {
								$i++;
								?>
								<div class="small-12 columns">
								<?php
										$theme->render('components/category/block_small', array(
										'article' => $article,
										'show_category' => false,
										'headshot' => false
									));
								?>
								</div>
								<?php
							}
						} else {
							?>
							<div class="small-12 columns">
								<p>No news is good news</p>
							</div>
							<?php
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="small-12 large-3 columns">
		<div class="row">
			<div class="small-12 medium-6 large-reset-order large-12 columns info-always-margin">
				<?php
					$theme->render('components/home/block_pdf');
				?>
			</div>
			<div class="small-12 medium-6 large-reset-order large-12 columns info-always-margin">
				<?php
					$theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => false));
				?>
			</div>
			<div class="small-12 medium-6 large-reset-order large-12 columns info-always-margin">
				<?php
					$theme->render('components/home/block_contribute');
				?>
			</div>

			<div class="small-12 medium-6 large-reset-order large-12 columns info-always-margin">
				<?php
					$theme->render('components/home/block_facebook');
				?>
			</div>
		</div>
	</div>
</div>

<div class="row features">
	<div class="small-12 columns">
		<div class="bar-text">Features</div>
	</div>
</div>

<?php
	$articles = (new \FelixOnline\Core\ArticleManager())
	->filter('published < NOW()')
	->filter('category = %i', array(\FelixOnline\Core\Settings::get('features_category_id')))
	->order(array('published', 'id'), 'DESC')
	->limit(0, 4)
	->values();
?>
<div class="row main-row top-row small-up-1 medium-up-4">
	<?php
		if($articles) {
			foreach($articles as $article) {
				$i++;
				?>
				<div class="columns">
				<?php
						$theme->render('components/category/block_normal', array(
						'article' => $article,
						'show_category' => false,
						'headshot' => false
					));
				?>
				</div>
				<?php
			}
		} else {
			echo "<p>Nothing found</p>";
		}
	?>
</div>

<div class="row comment">
	<div class="small-12 columns">
		<div class="bar-text">Comment</div>
	</div>
</div>
<div class="row main-row top-row small-up-1 medium-up-2 large-up-4">
	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('published < NOW()')
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('comment_category_id')))
		->order(array('published', 'id'), 'DESC')
		->limit(0, 3)
		->values();

		if($articles) {
			foreach($articles as $key => $article) {
	?>
				<div class="columns">
					<?php
						$theme->render('components/category/block_normal', array(
							'article' => $article,
							'show_category' => false,
							'headshot' => true
						));
					?>
				</div>
	<?php
			}
		} else {
			echo "<p>Nothing found</p>";
		}
	?>
	<div class="columns">
		<?php
			$theme->render('components/home/block_comments');
		?>
	</div>
</div>

<div class="row felix_default">
	<div class="small-12 columns">
		<div class="bar-text">Hot discussions</div>
	</div>
</div>
<div class="row top-row main-row">
	<div class="small-12 columns">
		<?php $theme->render('components/helpers/commented_stories', array('large' => true)); ?>
	</div>
</div>

<div class="row cands">
	<div class="small-12 columns">
		<div class="bar-text">Clubs, Societies, and Sports</div>
	</div>
</div>
<div class="row main-row top-row small-up-1 medium-up-2 large-up-4">
	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('published < NOW()')
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('cands_category_id')))
		->order(array('published', 'id'), 'DESC')
		->limit(0, 2)
		->values();

		if($articles) {
			foreach($articles as $key => $article) {
	?>
				<div class="columns">
					<?php
							$theme->render('components/category/block_normal', array(
								'article' => $article,
								'show_category' => false,
								'headshot' => false
							));
					?>
				</div>
	<?php
			}
		} else {
			echo "<p>Nothing found</p>";
		}
	?>

	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('published < NOW()')
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('sport_category_id')))
		->order(array('published', 'id'), 'DESC')
		->limit(0, 2)
		->values();

		if($articles) {
			foreach($articles as $key => $article) {
	?>
				<div class="columns">
					<?php
							$theme->render('components/category/block_normal', array(
								'article' => $article,
								'show_category' => false,
								'headshot' => false
							));
					?>
				</div>
	<?php
			}
		} else {
			echo "<p>Nothing found</p>";
		}
	?>
</div>

<div class="row felix_default">
	<div class="small-12 columns">
		<div class="bar-text">Trending stories</div>
	</div>
</div>

<div class="row main-row top-row">
	<div class="small-12 medium-9 columns">
		<?php $theme->render('components/helpers/trending_stories', array('large' => false)); ?>
	</div>
	<div class="small-12 medium-3 columns">
		<?php
			$theme->render('components/home/block_twitter');
		?>
	</div>
</div>

<?php $theme->render('components/globals/footer'); ?>
