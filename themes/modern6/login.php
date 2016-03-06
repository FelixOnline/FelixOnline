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
			<h1>You don't need to log in any more</h1>
			<p>Everything you could do whilst logged in is now available to logged out users.</p>
			<p>That includes posting, liking, and disliking comments.</p>
			<p><a href="<?php echo STANDARD_URL; ?>">Back to the home page</a></p>
		</div>
	<!-- End of page container -->
	</div>
<!-- End of page -->

<?php $theme->render('components/globals/footer'); ?>
