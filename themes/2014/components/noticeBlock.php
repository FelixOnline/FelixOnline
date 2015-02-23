<!-- Notices -->
	<?php
	$notices = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Notice', 'notices');
	$notices->filter('hidden = 0')
		->filter('end_time > NOW()')
		->filter('start_time < NOW()');

	if($no_frontpage_only) {
		$notices->filter('frontpage = TRUE');
	}

	$notices = $notices->order('sort_order', 'DESC')->values();

	if (!is_null($notices)) {
		?>
		<div class="row felix-pad-top">
			<div class="small-12 columns">
				<?php
		foreach($notices as $notice) { ?>
					<div data-alert class="alert-box notice"><small><?php echo strtoupper(date('D j M, g:i A', $notice->getStartTime())); ?></small> <?php echo $notice->getContent(); ?></div>
		<?php }
				?>
			</div>
		</div>
		<?php
	} ?>
