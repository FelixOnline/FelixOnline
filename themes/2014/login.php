<?php
$timing->log('login');
$header = array(
	'title' => 'Login to Felix Online'
); 

$theme->render('components/header', $header);
?>
<!-- Page wrapper -->
<div class="container_12">
	<!-- Page container -->
	<div class="container">
		<div class="row">
			<div class="medium-12 columns">
			<?php if($failed) { ?>
				<br>
				<div class="alert-box">
					Login failed. Please try again.
				</div>
			<?php }
				$theme->render('components/loginBox', array('location' => STANDARD_URL, 'nomodal' => true));
			?>
			</div>
		</div>
	</div>
	<!-- End of page container -->
</div>
<!-- End of page -->

<?php $timing->log('end of login');?>
<?php $theme->render('components/footer'); ?>
