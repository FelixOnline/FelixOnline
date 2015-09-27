<?php
$header = array(
	'title' => $page->getTitle().' - Felix Online'
); 

$theme->render('components/globals/header', $header);

?>
<!-- Page wrapper -->
	<div class="row">
		<div class="medium-8 columns">
			<h1><?php echo $page->getTitle(); ?></h1>
			<div class="content">
				<?php 
					/*
					 * Outputs content and evaluates any php code
					 */
					echo $page->getContent($csrf_token); 
				?>
			</div>
		</div>
		<div class="medium-4 columns">
				<?php $theme->render('components/helpers/block_advert', array('sidebar' => true)); ?>
				
				<?php $theme->render('components/helpers/block_popular'); ?>
		</div>
	<!-- End of page container -->
	</div>
<!-- End of page -->

<?php $theme->render('components/globals/footer'); ?>
