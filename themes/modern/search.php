
<?php
$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/globals/header', $header); 

?>
	<div class="row full-width">
		<div class="small-12 columns">
			<h1>Search</h1>
		</div>
	</div>
	<div class="row full-width">
		<div class="large-9 columns">
			<?php if (isset($toofew) && $toofew == true) { ?>
				<div class="alert-box">Uh oh! You did not specify enough search terms. Please try again!</div>
			<?php } else { ?>
				<div class="alert-box"><b>You searched for "<?php echo $query; ?>" and got <?php echo $article_count; ?> results.</b></div>
				<?php if ($article_count == 0 && $people_count == 0) { ?>
					<div class="alert-box">Uh oh! We couldn't find what you were looking for. Please try again!</div>
				<?php } else { ?>
					<?php if ($article_count !== 0) { ?>
						<?php $theme->render('components/helpers/month_article_view', array(
							'articles' => $articles,
							'show_category' => true,
							'headshot' => false
							)); ?>

						<div class="row">
							<div class="small-12 columns paginator-bit">
							<?php $theme->render('components/helpers/pagination_search', array(
								'page' => $page,
								'query' => $query
							)); ?>
							</div>
						</div>

						<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken('pagination'); ?>">
						<input type="hidden" name="pag-category" id="pag-category" value="1">
						<input type="hidden" name="pag-headshot" id="pag-headshot" value="0">
					<?php } else { ?>
						<div class="alert-box">No articles were found, but we did find some people.</div>
					<?php } ?>
				<?php } ?> 
			<?php } ?>
		</div>
		<div class="large-3 columns">
			<?php if (isset($people_count) && $people_count !== 0) { ?>
				<div class="info-box">
					<h1>People</h1>
					<ul class="search-people-list trending">
						<?php foreach ($people as $person) { ?>
							<li><a href="user/<?php echo $person['user'];?>/"><?php echo $person['name'];?></a></li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>

			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true)); ?>

			<?php $theme->render('components/home/block_contribute'); ?>

			<?php $theme->render('components/helpers/block_popular'); ?>

		</div>
		<!-- End of search container -->
	</div>
	
<?php $theme->render('components/globals/footer'); ?>
