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
	<div class="row">
		<div class="small-12 columns">
			<h1>Your email address has been confirmed</h1>
			<p>Thank you for confirming <?php echo $email; ?>.</p>
			<a href="<?php echo STANDARD_URL; ?>" class="button radius">Back to Felix Online</a>
		</div>
	</div>
	<!-- End of article wrapper -->

<?php $theme->render('components/globals/footer'); ?>
