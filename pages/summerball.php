<div class="container_12 summerball">
		<div class="grid_12" id="header">
			<h1>Summer Ball Feedback</h1>
			<div id="info">
				<p>All your comments will be kept anonymous, but we may use your year and subject. If you don't mind us using your name then please uncheck the box below.</p>
			</div>
		</div>
		
		<div class="grid_12">
			
			<?php
				
				if($_GET['success']) { ?>
					<div id="sent" style='display: block; margin-top: 5px; width: 170px; font-weight: normal;'>Thank you for your feedback!</div>
			<?php } else {
				
				global $sberror;
				if(!$sberror) {
					echo "<label class='error' style='display: block; margin-top: 5px; width: 350px;'>Opps we didn't recognise your username. Please try again.</label>";
				}
			?>
			
			<form action="" method="post" id="sbform">
				<ul>
					<li id="usercont">
						<?php if(!is_logged_in()) { ?>
						<label for="username">Imperial Username: <span>(required)</span></label>
						<input type="text" id="username" name="username" class="required" minlength="2"/>
						<?php } else { ?>
						<p>Name: <span><?php echo get_vname();?></span></p>
						<input type="hidden" id="username" name="username" value="<?php echo $uname; ?>"/>
						<?php } ?>
					</li>
					<li id="didyou">
						<p><b>Did you go to the summer ball?</b></p>
						<input type="radio" name="didyou" value="Yes" id="radioyes" class="required"/><label for="radioyes">Yes</label>
						<input type="radio" name="didyou" value="No" id="radiono"/><label for="radiono">No</label>
					</li>
					<li>
						<label for="comment" id="commentlabel">Comment: <span>(required)</span></label>
						<textarea id="commentbox" name="comment" class="required"><?php if(isset($_POST['comment'])) echo $_POST['comment'];?></textarea>
					</li>
					<li id="anoncont">
						<input type="checkbox" id="anon" name="anon" checked="checked"/><label for="anon">Keep my name anonymous</label>
					</li>
					<li>
						<input type="submit" value="Submit" id="submit" name="sbsubmit"/>
					</li>
				</ul>
			</form>
			
			<?php } ?>
		</div>
	</div>