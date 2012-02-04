<?php
$timing->log('page');
$header = array(
    'title' => $page->getTitle().' - Felix Online'
); 

$theme->render('header', $header);
?>
<!-- Page wrapper -->
<div class="container_12">
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8">
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
    <div class="grid_8 pull_4 <?php echo $page->getSlug(); ?>">
        <h2><?php echo $page->getTitle(); ?></h2>
        <div class="content">
            <?php 
                /*
                 * Outputs content and evaluates any php code
                 */
                echo $page->getContent(); 
            ?>
        </div>
	</div>
	<!-- End of contact container -->
</div>
<!-- End of page -->

<?php $timing->log('end of page');?>
<?php $theme->render('footer'); ?>
