<div id="connection-error">
<b>Uh oh!</b> We can't connect to the live blog. Is your internet connection working?
</div>

<div class="liveblog" data-liveblog data-liveblog-url="http://<?php echo \FelixOnline\Core\Settings::get('sprinkler_host'); ?>:<?php echo \FelixOnline\Core\Settings::get('sprinkler_port'); ?>/<?php echo $sprinkler_prefix; ?>" data-liveblog-id="<?php echo $blog_id; ?>">
	<div role="main" id="main">
		<div class="row">
			<div class="small-12 columns">
				<div class="masthead">
					<div class="row">
						<div class="small-12 columns">
							<div class="clearfix">
								<?php if($active): ?>
								<h2>Live updates</h2>
								<p class="info">No need to refresh, this page updates automatically.</p>
								<?php else: ?>
								<h2>Live updates</h2>
								<div class="alert-box">This live coverage has now ended.</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="medium-12 columns">
				<div class="feed loading">
					<div class="inner-feed">
					</div>
					<button id="loadPosts" class="button secondary"></button>
				</div>
			</div>
		</div>
	</div>
</div>