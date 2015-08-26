<?php

use FelixOnline\Exceptions;
use FelixOnline\Core\CurrentUser;

$header = array(
	'title' => 'Email Validation - '.'Felix Online'
);

$theme->render('components/header', $header); 

?>
	<!-- Article wrapper -->
	<div class="row felix-pad-top">
		<div class="small-12 columns">
			<h1>Your email address has been confirmed</h1>
			<p>Thank you for confirming <?php echo $email; ?>.</p>
		</div>
	</div>
	<!-- End of article wrapper -->

<?php $theme->render('components/footer'); ?>
