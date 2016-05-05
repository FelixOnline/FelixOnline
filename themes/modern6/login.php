<?php
$header = array(
	'title' => 'Login - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
); 

$theme->render('components/globals/header', $header);

?>
<!-- Page wrapper -->
	<div class="row main-row top-row">
		<div class="small-12 columns">
			<?php if($failed) { ?>
				<div class="callout alert">
					Login failed. Please try again.
				</div>
			<?php }
				$theme->render('components/modals/box_login', array('location' => STANDARD_URL, 'nomodal' => true));
			?>
		</div>
	<!-- End of page container -->
	</div>
<!-- End of page -->

<?php $theme->render('components/globals/footer'); ?>
