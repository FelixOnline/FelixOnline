
<?php
$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/globals/header', $header); 

?>
	<div class="row felix-pad-top">
		<div class="medium-8 columns">
			<?php if (isset($toofew) && $toofew == true) { ?>
				<div class="alert-box">Uh oh! You did not specify enough search terms. Please try again!</div>
			<?php } else { ?>
				<div class="alert-box"><b>You searched for "<?php echo $query; ?>" and got <?php echo $article_count; ?> results.</b></div>
				<?php if ($article_count == 0 && $people_count == 0) { ?>
					<div class="alert-box">Uh oh! We couldn't find what you were looking for. Please try again!</div>
				<?php } else { ?>
					<?php if ($article_count !== 0) { ?>
						<div class="felix-item-title felix-item-title felix-item-title-generic">
							<h2>Articles</h2>
						</div>

						<div class="pagination-content">
							<?php $theme->render('components/paginators/page_search'); ?>

							<?php $theme->render('components/helpers/pagination_search', array(
								'page' => $page,
								'query' => $query
							)); ?>
						</div>

						<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken('pagination'); ?>">
					<?php } else { ?>
						<div class="alert-box">No articles were found, but we did find some people - check the sidebar.</div>
					<?php } ?>
				<?php } ?> 
			<?php } ?>
		</div>
		<div class="medium-4 columns">
			<?php if (isset($people_count) && $people_count !== 0) { ?>
				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>People</h3>
				</div>
				<ul class="search-people-list">
					<?php foreach ($people as $person) { ?>
						<li><a href="user/<?php echo $person['user'];?>/"><?php echo $person['name'];?></a></li>
					<?php } ?>
				</ul>
			<?php } ?>

			<?php $theme->render('components/advert', array('sidebar' => true)); ?>

			<?php $theme->render('sidebar/contributionPolicy'); ?>

			<?php $theme->render('sidebar/mostPopular'); ?>

			<?php $theme->render('sidebar/twitter'); ?>
		</div>
		<!-- End of search container -->
	</div>
	
<?php $theme->render('components/globals/footer'); ?>
