<?php
$header = array(
	'title' => 'Issue Archive - '.'Felix Online'
);

$theme->render('components/globals/header', $header);
?>

<!-- Archive wrapper -->
<div class="row full-width">
	<div class="medium-5 large-3 columns medium-push-7 large-push-9">
		<?php $theme->render('components/home/block_pdf'); ?>

		<?php $theme->render('components/issuearchive/block_search', array('back' => true)); ?>

		<?php $theme->render('components/helpers/block_advert', array('sidebar' => true)); ?>
	</div>
	<div class="large-9 large-pull-3 medium-7 medium-pull-5 columns">
		<div class="felix-item-title felix-item-title felix-item-title-generic">
			<h1>Issue Archive: Search Results</h1>
			<div class="alert-box results">We found <?php echo count($search_results); ?> results</div>
		</div>
		<br>
		<div class="row issuecont">
			<?php if(!empty($search_results)) {
				foreach($search_results as $key => $issue) {
					if(($key + 1) % 4 == 0) { $last = true;	} else { $last = false; }
					$theme->render('components/issuearchive/issue', array(
						'issue' => $issue,
						'last' => $last
					));
					if($last): ?></div><div class="row issuecont"><?php endif;
				}
			} ?>
		</div>
	</div>    
</div>
<!-- End of archive wrapper -->
<?php $theme->render('components/globals/footer'); ?>
