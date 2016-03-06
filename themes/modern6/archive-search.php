<?php
$header = array(
	'title' => 'Issue Archive - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);
?>

<!-- Archive wrapper -->
<div class="row main-row top-row">
	<div class="medium-5 large-3 columns medium-push-7 large-push-9">
		<?php $theme->render('components/home/block_pdf'); ?>

		<?php $theme->render('components/issuearchive/block_search', array('back' => true)); ?>

		<?php $theme->render('components/helpers/block_advert', array('sidebar' => true)); ?>
	</div>
	<div class="large-9 large-pull-3 medium-7 medium-pull-5 columns">
		<div class="felix-item-title felix-item-title felix-item-title-generic">
			<h1>Issue Archive: Search Results</h1>
			<div class="callout secondary results">You searched for "<?php echo $query; ?>" and got <?php echo count($search_results); ?> results.</div>
				<?php if (count($search_results) == 0) { ?>
					<br>
					<div class="callout warning">
						Uh oh! We couldn't find what you were looking for. Please try again!
					</div>
				<?php } ?>
		</div>
		<br>
		<div class="row issuecont small-up-2 medium-up-3 large-up-4">
			<?php if(!empty($search_results)) {
				foreach($search_results as $key => $issue) {
					$theme->render('components/issuearchive/issue', array(
						'issue' => $issue
					));
				}
			} ?>
		</div>
	</div>    
</div>
<!-- End of archive wrapper -->
<?php $theme->render('components/globals/footer'); ?>
