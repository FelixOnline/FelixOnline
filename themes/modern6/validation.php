<?php

use FelixOnline\Exceptions;
use FelixOnline\Core\CurrentUser;

$header = array(
	'title' => 'Email Validation - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header); 

?>
	<!-- Article wrapper -->
	<div class="row main-row top-row">
		<div class="small-12 columns">
			<h1>Your email address has been verified</h1>
			<p>Thank you for verifying <?php echo $email; ?>.</p>
			<p>Any comments you have made using this address will now show up, and you won't need to verify this email again.</p>
			<a href="<?php echo STANDARD_URL; ?>" class="button">Back to Felix Online</a>
		</div>
	</div>
	<!-- End of article wrapper -->

<?php $theme->render('components/globals/footer'); ?>
