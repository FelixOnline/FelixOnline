<?php
$timing->log('505 error');

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header); 
$timing->log('after header');

?>
	<!-- Article wrapper -->
	<div class="row felix-pad-top">
		<div class="small-12 columns">
			<?php
				$notify = false;
				require(BASE_DIRECTORY.'/errors/error.php');
			?>
		</div>
	</div>
	<!-- End of article wrapper -->

<?php $theme->render('components/footer'); ?>
