<?php

$header = array(
	'meta' => '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);

?>
    <div class="row full-width top-row" data-equalizer="top">
      <div class="small-12 large-9 columns">
        <div class="row">
          <div class="small-12 large-8 columns">
            <div class="carousel-block" data-equalizer="carousel">
				<?php
					foreach($spinner as $article) {
						$theme->render('components/category/block_carousel', array(
							'article' => $article->getArticle()
						));
					}
				?>
			</div>
          </div>
          <div class="small-12 large-4 columns" data-equalizer-watch="carousel">
            <div class="row" data-equalizer-mq="medium-only">
				<hr class="month-divider news top-divider">
				<div class="small-12 columns">
					<p class="section-date top-section-date news">Latest news</p>
				</div>
				<div data-equalizer="news-side">
					<?php
						$articles = (new \FelixOnline\Core\ArticleManager())
							->filter('category = %i', array(\FelixOnline\Core\Settings::get('news_category_id')))
							->enablePublishedFilter()
							->limit(0, 5)
							->values();
						$i = 0;

						if($articles) {
							foreach($articles as $article) {
								$i++;
								?>
								<div class="medium-6 large-12 columns <?php if($i == 5): echo 'end'; endif; ?>">
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
		<?php
			$thisSection = new \FelixOnline\Core\Category(\FelixOnline\Core\Settings::get('features_category_id'));
		?>
		<hr class="month-divider top-divider <?php echo $thisSection->getCat(); ?>">
		<div class="small-12 columns">
			<p class="section-date <?php echo $thisSection->getCat(); ?>"><?php echo $thisSection->getLabel(); ?></p>
		</div>
		<?php
			$articles = (new \FelixOnline\Core\ArticleManager())
			->filter('category = %i', array(\FelixOnline\Core\Settings::get('features_category_id')))
			->enablePublishedFilter()
			->limit(0, 3)
			->values();
		?>
       	<div class="row" data-equalizer="news">
       	<?php
       		if($articles):
       	?>
          <div class="small-12 medium-4 columns">
          	<?php
          		$theme->render('components/category/block_normal', array(
					'article' => $articles[0],
					'equalizer' => 'news',
					'show_category' => false,
					'show_blurb' => false,
					'headshot' => false
				));
			?>
          </div>
          <div class="small-12 medium-4 columns">
          	<?php
          		$theme->render('components/category/block_normal', array(
					'article' => $articles[1],
					'equalizer' => 'news',
					'show_category' => false,
					'show_blurb' => false,
					'headshot' => false
				));
			?>
          </div>
          <div class="small-12 medium-4 columns">
          	<?php
          		$theme->render('components/category/block_normal', array(
					'article' => $articles[2],
					'equalizer' => 'news',
					'show_category' => false,
					'headshot' => false
				));
			?>
          </div>
        <?php
        	else:
        		echo '<p>Nothing found</p>';
        	endif;
        ?>
        </div>
      </div>
      <div class="small-12 large-3 columns" data-equalizer-watch="top">
        <div class="row">
          <div class="small-12 medium-6 medium-push-6 large-reset-order large-12 columns info-always-margin show-for-large-up">
            <?php
            	$theme->render('components/home/block_pdf');
            ?>
          </div>
          <div class="small-12 medium-6 medium-push-6 large-reset-order large-12 columns info-always-margin">
          	<?php
          		$theme->render('components/helpers/block_popular');
          	?>
          </div>
          <div class="small-12 medium-6 medium-pull-6 large-reset-order large-12 columns info-always-margin">
            <div class="hide-for-large-up">
            <?php
            	$theme->render('components/home/block_pdf');
            ?>
            </div>
           	<?php
            	$theme->render('components/home/block_contribute'); // Includes advert

            	$theme->render('components/home/block_facebook');
            ?>
          </div>
        </div>
      </div>
    </div>

	<?php
		$thisSection = new \FelixOnline\Core\Category(\FelixOnline\Core\Settings::get('comment_category_id'));
	?>
    <hr class="month-divider <?php echo $thisSection->getCat(); ?>">
    <div class="row full-width" data-equalizer="opinion">
      <div class="small-12 columns">
        <p class="section-date <?php echo $thisSection->getCat(); ?>"><?php echo $thisSection->getLabel(); ?></p>
      </div>

	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('comment_category_id')))
		->enablePublishedFilter()
		->limit(0, 3)
		->values();

		$i = 0;
		if($articles) {
			foreach($articles as $key => $article) {
				$i++;
		?>
	      <div class="small-12 medium-6 large-3 columns<?php if($i == count($articles)): ?> end<?php endif; ?>">
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
		} else {
			echo "<p>Nothing found</p>";
		}
	?>
		<div class="small-12 medium-6 large-3 columns" data-equalizer-watch="opinion">
          	<?php
          		$theme->render('components/home/block_comments');
          	?>
		</div>
	</div>

	<?php
		$thisSection = new \FelixOnline\Core\Category(\FelixOnline\Core\Settings::get('cands_category_id'));
		$thisSection2 = new \FelixOnline\Core\Category(\FelixOnline\Core\Settings::get('sport_category_id'));
	?>
    <hr class="month-divider <?php echo $thisSection->getCat(); ?>">
    <div class="row full-width" data-equalizer="cands">
      <div class="small-12 columns">
        <p class="section-date <?php echo $thisSection->getCat(); ?>"><?php echo $thisSection->getLabel(); ?>, and <?php echo $thisSection2->getLabel(); ?></p>
      </div>

	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('cands_category_id')))
		->enablePublishedFilter()
		->limit(0, 2)
		->values();

		$i = 0;

		if($articles) {
			foreach($articles as $key => $article) {
				$i++;
		?>
	      <div class="small-12 medium-6 large-3 columns<?php if($i == count($articles)): ?> end<?php endif; ?>">
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
		} else {
			echo "<p>Nothing found in ".$thisSection->getLabel()."</p>";
		}
	?>

	<?php
		$articles = (new \FelixOnline\Core\ArticleManager())
		->filter('category = %i', array(\FelixOnline\Core\Settings::get('sport_category_id')))
		->enablePublishedFilter()
		->limit(0, 2)
		->values();

		$i = 0;

		if($articles) {
			foreach($articles as $key => $article) {
				$i++;
		?>
	      <div class="small-12 medium-6 large-3 columns<?php if($i == count($articles)): ?> end<?php endif; ?>">
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
		} else {
			echo "<p>Nothing found in ".$thisSection2->getLabel()."</p>";
		}
	?>

	</div>

    <hr class="month-divider features">
    <div class="row full-width">
      <div class="small-12 columns">
        <p class="section-date features">Best of the paper</p>
      </div>
      	<div class="small-12 large-9 columns" data-equalizer="features-end">
      		<div class="row">
		<?php
			$i = 0;

			if($thisweek) {
				foreach($thisweek as $article) {
					$i++;

					if($i == count($thisweek)) {
						$end = ' end';
					} else {
						$end = '';
					}

			?>
					<div class="small-12 medium-6 large-4 columns<?php echo $end; ?>">
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

				}
			} else {
				echo "<p>Nothing found</p>";
			}
		?>
			</div>
		</div>
		<div class="small-12 large-3 columns">
		<?php
	      	$theme->render('components/home/block_twitter');
		?>
		</div>
	</div>

	<!-- End of front page articles -->


<!-- End of featured bar -->
<?php $theme->render('components/globals/footer'); ?>
