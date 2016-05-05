<?php
	// location
	if(!isset($location)) {
		$location = Utility::currentPageURL();
	}
?>
<?php if(!isset($nomodal) || !$nomodal): ?>
<div id="loginModal" class="reveal-modal small info-box" data-reveal>
<?php endif; ?>
	<h1>Login to Felix Online</h1>
	<form action="<?php echo AUTHENTICATION_PATH; ?>login/?goto=<?php echo $location; ?>" id="loginForm" class="" method="post">
		<div class="row">
			<div class="medium-12 small-12 columns">
				<div class="alert-box ajax-login-error" style="display: none;">
				</div>
				<div class="row">
					<div class="small-3 columns">
						<label for="user" class="right inline">IC Username</label>
					</div>
					<div class="small-9 columns">
						<input type="text" name="username" id="user" class="small" placeholder="a.gast"/>
					</div>
				</div>
				<div class="row">
					<div class="small-3 columns">
						<label for="password" class="right inline">IC Password</label>
					</div>
					<div class="small-9 columns">
						<input type="password" name="password" id="password" placeholder="•••••••••••"/>
					</div>
				</div>
				<div class="row">
					<div class="small-3 columns">
						<label for="rememberButton" class="right">Remember Me?</label>
					</div>
					<div class="small-9 columns">
						<input type="checkbox" name="remember" id="rememberButton" value="rememberme" checked="checked" />
					</div>
				</div>
				<div class="row">
					<div class="small-3 small-centered columns">
						<input type="submit" value="Login (SSL)" name="login" id="submit" class="login-button button small radius" />
					</div>
				</div>
			</div>
		</div>
	</form>
<?php if(!isset($nomodal) || !$nomodal): ?>
	<button class="close-button" data-close aria-label="Close modal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<?php endif; ?>