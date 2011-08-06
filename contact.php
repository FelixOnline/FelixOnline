<div class="container_12">
	
	<?php
		/*TODO:
	
		*/
	?>
		
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8 contact">
		<?php 
			include_once('sidebar/fbActivity.php');
			include_once('sidebar/mediaBox.php');
			include_once('sidebar/mostPopular.php');
		?>
	</div>
	<!-- End of sidebar -->
		
	<!-- Contact container -->
	<div class="grid_8 pull_4">
		<h2>Contact Us</h2>
		
		<p>You can email us at <?php echo hide_email('felix@imperial.ac.uk');?> or use the contact form below: </p>
		
		<form action="" method="post" id="contactform">
			
			<label for="name">Name: <span>(optional)</span></label><input type="text" id="name" name="name" />
			<label for="email">Email: <span>(optional)</span></label><input type="text" id="email" name="email" />
			<label for="message">Message: <span>(required)</span></label><textarea id="message" name="message"></textarea>
			<label for="message" class="error">Please write a message</label>
			<div class="clear"></div>
			<input type="submit" value="Send" id="submit" name="submit"/>
			<span id="sending" style="display: none;">Sending...</span>
		</form>
		<span id="sent" style="display: none;">Thank you!</span>
		
	</div>
	<!-- End of contact container -->

	<div class="clear"></div>

</div>