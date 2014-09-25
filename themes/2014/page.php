<?php
$timing->log('page');
$header = array(
	'title' => $page->getTitle().' - Felix Online'
); 

$theme->render('components/header', $header);
?>
<!-- Page wrapper -->
	<div class="row felix-pad-top">
		<div class="medium-8 columns">
			<h1><?php echo $page->getTitle(); ?></h1>
			<div class="content">
				<?php 
					/*
					 * Outputs content and evaluates any php code
					 */
					echo $page->getContent(); 
				?>
			</div>
		</div>
		<div class="medium-4 columns">
				<?php $theme->render('sidebar/contributionPolicy'); ?>

				<?php $theme->render('sidebar/mostPopular'); ?>

				<?php $theme->render('sidebar/twitter'); ?>
		</div>
	<!-- End of page container -->
	</div>
<!-- End of page -->

<?php $timing->log('end of page');?>
<?php $theme->render('components/footer'); ?>
