<?php
$timing->log('frontpage');

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header);
$timing->log('after header');

?>
		<div class="row felix-pad-top">
			<div class="medium-8 columns">
				<div class="felix-featured-slider">
					<div>
						<div class="felix-featured-caption">Caption One.</div>
						<div class="felix-featured-image"><img src="http://placekitten.com/g/1200/1200" alt="slide 1"></div>
						<div class="felix-featured-subcaption">Blah blah blah.</div>
					</div>
					<div>
						<div class="felix-featured-caption">Caption Two.</div>
						<div class="felix-featured-image"><img src="http://placekitten.com/g/1000/1000" alt="slide 2"></div>
						<div class="felix-featured-subcaption">Blah blah blah.</div>
					</div>
					<div>
						<div class="felix-featured-caption">Caption Three.</div>
						<div class="felix-featured-image"><img src="http://placekitten.com/g/1200/1000" alt="slide 3"></div>
						<div class="felix-featured-subcaption">Blah blah blah.</div>
					</div>
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
					->limit(0, 11)
					->values();

					foreach($articles as $key => $article) {
						if($key >= 7) { continue; }
						$theme->render('components/articlelist/frontpage_news', array(
							'article' => $article
						));
					}
				?>
				<div class="row">
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/frontpage_news_secondary', array(
								'article' => $articles[7]
							));
						?>
					</div>
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/frontpage_news_secondary', array(
								'article' => $articles[8]
							));
						?>
					</div>
				</div>
				<div class="row">
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/frontpage_news_secondary', array(
								'article' => $articles[9]
							));
						?>
					</div>
					<div class="medium-6 columns">
						<?php
							$theme->render('components/articlelist/frontpage_news_secondary', array(
								'article' => $articles[10]
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

				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>write for us!</h3>
				</div>
				<p>Interested in becoming a news reporter? Or just have a favourite something to share with Imperial? Write for Felix - it's easy!</p>
				<p>Got a tip you'd like to share? We welcome anonymous messages too.</p>
				<center><a class="button" href="<?php echo STANDARD_URL; ?>issuearchive/">Find out how to contribute</a></center>	

				<div class="felix-item-title felix-item-title-generic">
					<h3>@feliximperial on twitter</h3>
				</div>
				<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/feliximperial"  data-widget-id="346347929105219584" data-chrome="noheader nofooter noborders noscrollbar transparent" data-tweet-limit="4">Tweets by @feliximperial</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

			</div>
		</div>

	<!-- End of front page articles -->
	<?php $timing->log('end of frontpage'); ?>


<!-- End of featured bar -->
<?php $theme->render('footer'); ?>
