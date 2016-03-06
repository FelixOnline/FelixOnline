		<div id="archivesearchbar" class="info-box info-title-only">
			<h1>Archive Search</h1>
		</div>
		<div class="info-secondary-box pad">
			<form method="get" action="">
				<input type="text" name="q" size="40" placeholder="Type and press enter..." id="searchinput" />
			</form>
			<?php
				if($back):
			?>
				<div class="text-center">
					<a href="<?php STANDARD_URL; ?>issuearchive" class="button small radius">Back to Issue Archive</a>
				</div>
			<?php
				endif;
			?>
		</div>