<?php

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header);

$theme->render('components/noticeBlock', array('no_frontpage_only' => false));

?>
		<div class="row felix-pad-top">
			<div class="medium-8 columns">
				<div class="felix-featured-slider">
					<?php
						foreach($spinner as $article) {
							if(!$article->getArticle()->getCategory()->getActive() ||
								(!$currentuser->isLoggedIn() && $article->getArticle()->getCategory()->getSecret())) {
								continue;
							}

							$theme->render('components/articlelist/featured_spinner', array(
								'article' => $article
							));
						}
					?>
				</div>
			</div>
			<div class="medium-4 columns show-for-medium-up">
				<?php $theme->render('sidebar/downloadBlock'); ?>
				<a href="<?php echo STANDARD_URL.'/contribute'; ?>" class="button expand radius">Get involved with <i>Felix</i> - it's easy</a>
				<?php $theme->render('sidebar/fbLikeBox', array("well" => false)); ?>
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
					->filter('category = %i', array(\FelixOnline\Core\Settings::get('news_category_id')))
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
								'article' => $articles[8],
								'show_authors' => false,
								'show_dates' => true
							));
						?>
					</div>
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/article_small', array(
								'article' => $articles[9],
								'show_authors' => false,
								'show_dates' => true
							));
						?>
					</div>
				</div>
				<div class="row">
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/article_small', array(
								'article' => $articles[10],
								'show_authors' => false,
								'show_dates' => true
							));
						?>
					</div>
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/article_small', array(
								'article' => $articles[11],
								'show_authors' => false,
								'show_dates' => true
							));
						?>
					</div>
				</div>
			</div>
			<div class="medium-4 small-12 columns">
				<div class="felix-item-title felix-item-title felix-item-title-comment">
					<h3>Comment</h3>
				</div>
				<br>
				<?php
					$articles = (new \FelixOnline\Core\ArticleManager())
					->filter('published < NOW()')
					->filter('category = %i', array(\FelixOnline\Core\Settings::get('comment_category_id')))
					->order('published', 'DESC')
					->limit(0, 2)
					->values();

					foreach($articles as $key => $article) {
						$theme->render('components/articlelist/article_small', array(
							'article' => $article,
							'show_dates' => false,
							'show_authors' => true
						));
					}
				?>
				<div class="felix-item-title felix-item-title felix-item-title-cands">
					<h3>Clubs and Socs</h3>
				</div>
				<br>
				<?php
					$articles = (new \FelixOnline\Core\ArticleManager())
					->filter('published < NOW()')
					->filter('category = %i', array(\FelixOnline\Core\Settings::get('cands_category_id')))
					->order('published', 'DESC')
					->limit(0, 3)
					->values();

					foreach($articles as $key => $article) {
						$theme->render('components/articlelist/article_small', array(
							'article' => $article,
							'show_dates' => false,
							'show_authors' => true
						));
					}
				?>
				<div class="felix-item-title felix-item-title felix-item-title-sports">
					<h3>Sport</h3>
				</div>
				<br>
				<?php
					$articles = (new \FelixOnline\Core\ArticleManager())
					->filter('published < NOW()')
					->filter('category = %i', array(\FelixOnline\Core\Settings::get('sport_category_id')))
					->order('published', 'DESC')
					->limit(0, 3)
					->values();

					foreach($articles as $key => $article) {
						$theme->render('components/articlelist/article_small', array(
							'article' => $article,
							'show_dates' => true,
							'show_authors' => false
						));
					}
				?>
				<div class="show-for-small-only">
					<?php $theme->render('sidebar/downloadBlock'); ?>
					<a href="<?php echo STANDARD_URL.'/contribute'; ?>" class="button expand">Get involved with <i>Felix</i> - it's easy</a>
					<?php $theme->render('sidebar/fbLikeBox', array("well" => false)); ?>
				</div>
				<?php
					$theme->render('sidebar/mostPopular');

					$theme->render('sidebar/twitter');
				?>
			</div>
		</div>

	<!-- End of front page articles -->


<!-- End of featured bar -->
<?php $theme->render('components/footer'); ?>
