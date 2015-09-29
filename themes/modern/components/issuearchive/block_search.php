		<div id="archivesearchbar" class="info-box">
			<h1>Archive Search</h1>
			<br>
			<form method="get" action="">
				<input type="text" name="q" size="40" placeholder="Type your query and press enter..." id="searchinput" />
			</form>
		</div>

		<?php
			if($back):
		?>
		<div class="info-box text-center">
			<a href="<?php STANDARD_URL; ?>issuearchive" class="button small radius">Back to Issue Archive</a>
		</div>
		<?php
			endif;
		?>