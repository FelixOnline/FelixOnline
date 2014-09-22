<?php
$timing->log('page');
$header = array(
	'title' => $page->getTitle().' - Felix Online'
); 

$theme->resources->addJS(array('contact.js'));

$theme->render('header', $header);
?>
<!-- Page wrapper -->
<div class="container_12">
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8 contact">
		<?php 
			$theme->setSidebar(array(
				'fbActivity', 
				//'mediaBox',
				'mostPopular'
			));
			$theme->renderSidebar();
		?>
	</div>
	<!-- End of sidebar -->

	<!-- Page container -->
	<div class="grid_8 pull_4">
		<h2><?php echo $page->getTitle(); ?></h2>
		<div class="content">
			<?php echo $page->getContent(); ?>
		</div>
	</div>
	<!-- End of contact container -->
</div>
<!-- End of page -->

<?php $timing->log('end of page');?>
<?php $theme->render('footer'); ?>
