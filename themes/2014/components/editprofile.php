<?php
	// location
	if(!isset($location)) {
		$location = Utility::currentPageURL();
	}
?>
<div id="editProfileModal" class="reveal-modal" data-reveal>
	<form action="???" id="???" method="post">
		<h3>Update your contact details</h3>
		<p>You can update the details which show on your profile page here. Remember, these will be public to the world, but it helps people get in touch if they read an article by you they like. People can access your profile page from any article written by you.</p>
		<p>If you leave the text box for a contact method blank, we'll hide the contact method from your profile page - so if you aren't happy sharing something feel free just to delete it.</p>
		<div class="row">
			<div class="small-3 columns">
				<label for="email" class="right inline">Email address</label>
			</div>
			<div class="small-9 columns">
				<input type="text" name="email" id="email" class="small" placeholder="" value="<?php echo $user->getEmail(); ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="small-3 columns">
				<label for="facebook" class="right inline">Facebook URL</label>
			</div>
			<div class="small-9 columns">
				<input type="text" name="facebook" id="facebook" placeholder="" value="<?php echo $user->getFacebook(); ?>"/>
				<p class="form-help">This can be for your personal profile, a page, a group, anything!</p>
			</div>
		</div>
		<div class="row">
			<div class="small-3 columns">
				<label for="twitter" class="right inline">Twitter</label>
			</div>
			<div class="small-9 columns">
				<div class="row collapse">
					<div class="small-3 large-2 columns">
						<span class="prefix">@</span>
					</div>
					<div class="small-9 large-10 columns">
						<input type="text" name="twitter" id="twitter" placeholder="" value="<?php echo $user->getTwitter(); ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-3 columns">
				<label for="website" class="right inline">Website URL</label>
			</div>
			<div class="small-9 columns">
				<input type="text" name="website" id="website" placeholder="" value="<?php echo $user->getWebsiteurl(); ?>"/>
				<p class="form-help">Perhaps you have a personal website?</p>
			</div>
		</div>
		<div class="row">
			<div class="small-3 columns">
				<label for="website-name" class="right inline">Website Name</label>
			</div>
			<div class="small-9 columns">
				<input type="text" name="website-name" id="website-name" placeholder="" value="<?php echo $user->getWebsitename(); ?>"/>
				<p class="form-help">We need a name to show for your website too.</p>
			</div>
		</div>
		<div class="row">
			<div class="small-3 push-3 columns">
				<input type="submit" value="Save" name="save" id="submit" class="button small radius" />
			</div>
		</div>
	</form>
	<a href="<?php echo $location; ?>" class="close-reveal-modal">&#215;</a>
</div>
