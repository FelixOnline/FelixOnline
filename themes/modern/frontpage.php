<?php

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/globals/header', $header);

$categoryManager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Category', 'category');

if(!$currentuser->isLoggedIn()) {
	$categoryManager = $categoryManager->filter('secret = 0');
}

?>
    <div class="row full-width top-row" data-equalizer="top">
      <div class="small-12 large-9 columns">
        <div class="row">
          <div class="small-12 large-8 columns">
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
		<?php
			$articles = (new \FelixOnline\Core\ArticleManager())
			->filter('published < NOW()')
			->filter('category = %i', array(\FelixOnline\Core\Settings::get('news_category_id')))
			->order('published', 'DESC')
			->join($categoryManager, null, 'category')
			->limit(0, 5)
			->values();
		?>
          <div class="small-12 large-4 columns" data-equalizer-watch="carousel">
            <div class="row" data-equalizer="news-side">
              <div class="medium-6 large-12 columns">
              	<?php
              		$theme->render('components/category/block_normal', array(
						'article' => $articles[0],
						'equalizer' => 'news-side',
						'show_category' => false,
						'headshot' => false
					));
				?>
              </div>
              <div class="medium-6 large-12 columns">
              	<?php
              		$theme->render('components/category/block_normal', array(
						'article' => $articles[1],
						'equalizer' => 'news-side',
						'show_category' => false,
						'headshot' => false
					));
				?>
              </div>
            </div>
          </div>
        </div>
       	<div class="row" data-equalizer="news">
          <div class="small-12 medium-4 columns" data-equalizer-watch="news">
          	<?php
          		$theme->render('components/category/block_normal', array(
					'article' => $articles[2],
					'equalizer' => 'news',
					'show_category' => false,
					'headshot' => false
				));
			?>
          </div>
          <div class="small-12 medium-4 columns" data-equalizer-watch="news">
          	<?php
          		$theme->render('components/category/block_normal', array(
					'article' => $articles[3],
					'equalizer' => 'news',
					'show_category' => false,
					'headshot' => false
				));
			?>
          </div>
          <div class="small-12 medium-4 columns" data-equalizer-watch="news">
          	<?php
          		$theme->render('components/category/block_normal', array(
					'article' => $articles[4],
					'equalizer' => 'news',
					'show_category' => false,
					'headshot' => false
				));
			?>
          </div>
        </div>
      </div>
      <div class="small-12 large-3 columns" data-equalizer-watch="top">
        <div class="row">
          <div class="small-12 medium-6 large-12 columns">
          	<?php
    			$theme->render('components/home/block_pdf');
			?>
          </div>
          <div class="small-12 medium-6 large-12 columns">
          	<?php
          		$theme->render('components/helpers/block_popular');
          	?>
          </div>
          <div class="small-12 medium-6 large-12 columns">
            <?php
            	$theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => false));
            ?>
          </div>
          <div class="small-12 medium-6 large-12 columns">
            <?php
            	$theme->render('components/home/block_contribute');
            ?>
          </div>
          <div class="small-12 medium-6 large-12 columns">
          	<?php
          		$theme->render('components/home/block_facebook');
          	?>
          </div>
        </div>
      </div>
    </div>

    <hr class="month-divider">
    <div class="row full-width" data-equalizer="opinion">
      <div class="small-12 columns">
        <p class="section-date">Opinion</p>
      </div>

	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('published < NOW()')
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('comment_category_id')))
		->order('published', 'DESC')
		->join($categoryManager, null, 'category')
		->limit(0, 3)
		->values();

		foreach($articles as $key => $article) {
	?>
      <div class="small-12 medium-6 large-3 columns" data-equalizer-watch="opinion">
    <?php
      	$theme->render('components/category/block_normal', array(
			'article' => $article,
			'equalizer' => 'opinion',
			'show_category' => false,
			'headshot' => true
		));
    ?>
      </div>
    <?php
		}
	?>
		<div class="small-12 medium-6 large-3 columns" data-equalizer-watch="opinion">
          	<?php
          		$theme->render('components/home/block_comments');
          	?>
		</div>
	</div>

    <hr class="month-divider">
    <div class="row full-width" data-equalizer="cands">
      <div class="small-12 columns">
        <p class="section-date">Clubs, Societies, and Sports</p>
      </div>

	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('published < NOW()')
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('cands_category_id')))
		->order('published', 'DESC')
		->join($categoryManager, null, 'category')
		->limit(0, 2)
		->values();

		foreach($articles as $key => $article) {
	?>
      <div class="small-12 medium-6 large-3 columns" data-equalizer-watch="cands">
    <?php
      	$theme->render('components/category/block_normal', array(
			'article' => $article,
			'equalizer' => 'cands',
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
		->order('published', 'DESC')
		->join($categoryManager, null, 'category')
		->limit(0, 2)
		->values();

		foreach($articles as $key => $article) {
	?>
      <div class="small-12 medium-6 large-3 columns" data-equalizer-watch="cands">
    <?php
      	$theme->render('components/category/block_normal', array(
			'article' => $article,
			'equalizer' => 'cands',
			'show_category' => false,
			'headshot' => false
		));
    ?>
      </div>
    <?php
		}
	?>

	</div>

    <hr class="month-divider">
    <div class="row full-width">
      <div class="small-12 columns">
        <p class="section-date">Best of the paper</p>
      </div>
      	<div class="small-12 large-9 columns">
		<?php
			foreach($thisweek as $article) {
				if(!$article->getArticle()->getCategory()->getActive() ||
					(!$currentuser->isLoggedIn() && $article->getArticle()->getCategory()->getSecret())) {
					continue;
				}

				if(floor($i/3) == $i/3) {
					echo '<div class="row features-row" data-equalizer="features-end">';
				}

				$i++;

				if($i == count($thisweek) && (floor($i/3) == $i/3)) {
					$end = ' end';
				} else {
					$end = '';
				}

		?>
			<div class="small-12 medium-6 large-4 columns<?php echo $end; ?>" data-equalizer-watch="features-end">
		<?php
		      	$theme->render('components/category/block_normal', array(
					'article' => $article->getArticle(),
					'equalizer' => 'features-end',
					'show_category' => true,
					'headshot' => false
				));
		?>
			</div>
		<?php

				if(floor($i/3) == $i/3) {
					echo '</div>';
				}
			}
		?>
		</div></div>
		<div class="small-12 large-3 columns">
		<?php
	      	$theme->render('components/home/block_twitter');
		?>
		</div>
	</div>

	<!-- End of front page articles -->


<!-- End of featured bar -->
<?php $theme->render('components/globals/footer'); ?>
