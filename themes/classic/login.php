<?php
$timing->log('login');
$header = array(
    'title' => 'Login to Felix Online'
); 

$theme->render('header', $header);
?>
<!-- Page wrapper -->
<div class="container_12">
	<!-- Page container -->
    <div class="grid_12 login-page">
        <div class="content">
            <?php if($failed) { ?>
                <div class="error">
                    <p>Login failed. Please try again.</p>
                </div>
            <?php }
                $theme->render('loginBox', array('location' => STANDARD_URL));
            ?>
        </div>
	</div>
	<!-- End of page container -->
</div>
<!-- End of page -->

<?php $timing->log('end of login');?>
<?php $theme->render('footer'); ?>
