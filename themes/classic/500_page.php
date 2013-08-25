<?php
$timing->log('505 error');

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header); 
$timing->log('after header');

?>
	<!-- Article wrapper -->
	<div class="container_12">
		<div class="grid_12 error">
			<?php require(BASE_DIRECTORY.'/errors/error.php'); ?>
		</div>
		<div class="clear"></div>
	</div>
	<!-- End of article wrapper -->

<?php $theme->render('footer'); ?>
