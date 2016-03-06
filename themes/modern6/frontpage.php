<?php

$header = array(
	'meta' => '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);

$categoryManager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Category', 'category');

if(!$currentuser->isLoggedIn()) {
	$categoryManager = $categoryManager->filter('secret = 0');
}

?>

<div class="row main-row top-row" data-equalizer="top">
	<div class="small-12 large-9 columns">
		<div class="row">
			<div class="small-12 medium-8 columns">
				<div class="carousel-block" data-equalizer="carousel">
				<?php
					foreach($spinner as $article) {
						if(!$article->getArticle()->getCategory()->getActive() ||
							(!$currentuser->isLoggedIn() && $article->getArticle()->getCategory()->getSecret())) {
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
							->join($categoryManager, null, 'category')
							->limit(0, 5)
							->values();
						$i = 0;

						foreach($articles as $article) {
							$i++;
							?>
							<div class="small-6 medium-12 columns">
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
					?>

					<div class="small-6 medium-12 columns">
						<?php $theme->render('components/home/block_facebook'); ?>
					</div>
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
					$theme->render('components/helpers/block_popular');
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
	->join($categoryManager, null, 'category')
	->limit(0, 3)
	->values();
?>
<div class="row main-row top-row">
	<div class="small-12 large-9 columns">
		<div class="row small-up-1 medium-up-3 large-up-3">
			<?php
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
			?>
		</div>
	</div>
	<div class="small-12 large-3 columns">
		<?php
			$theme->render('components/home/block_contribute'); // Includes advert
		?>
	</div>
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
		->join($categoryManager, null, 'category')
		->limit(0, 3)
		->values();

		$i = 0;
		foreach($articles as $key => $article) {
			$i++;
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
	?>
	<div class="columns">
		<?php
			$theme->render('components/home/block_comments');
		?>
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
		->join($categoryManager, null, 'category')
		->limit(0, 2)
		->values();

		$i = 0;
		foreach($articles as $key => $article) {
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
	?>

	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('published < NOW()')
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('sport_category_id')))
		->order(array('published', 'id'), 'DESC')
		->join($categoryManager, null, 'category')
		->limit(0, 2)
		->values();

		$i = 0;
		foreach($articles as $key => $article) {
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
	?>
</div>

<div class="row features">
	<div class="small-12 columns">
		<div class="bar-text">Best of the paper</div>
	</div>
</div>
<div class="row main-row top-row">
	<div class="small-12 medium-9 columns">
		<div class="row small-up-1 medium-up-2 large-up-3">
		<?php
			$i = 0;
			foreach($thisweek as $article) {
				if(!$article->getArticle()->getCategory()->getActive() ||
					(!$currentuser->isLoggedIn() && $article->getArticle()->getCategory()->getSecret())) {
					continue;
				}

				$i++;
		?>
				<div class="columns">
		<?php
					$theme->render('components/category/block_normal', array(
						'article' => $article->getArticle(),
						'show_category' => true,
						'headshot' => false
					));
		?>
				</div>
		<?php
			}
		?>
		</div>
	</div>
	<div class="small-12 medium-3 columns">
		<?php
			$theme->render('components/home/block_twitter');
		?>
	</div>
</div>

<?php $theme->render('components/globals/footer'); ?>
