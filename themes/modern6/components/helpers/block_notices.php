<!-- Notices -->
	<?php
	$notices = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Notice', 'notices');
	$notices->filter('hidden = 0')
		->filter('end_time > NOW()')
		->filter('start_time < NOW()');

	if($all_pages) {
		$notices->filter('frontpage = TRUE');
	}

	$notices = $notices->order('sort_order', 'DESC')->values();

	if (!is_null($notices)) {
		?>
		<div class="row main-row top-row">
			<div class="small-12 columns">
				<?php
		foreach($notices as $notice) { ?>
			<?php
				$text = strip_tags($notice->getContent(), '<b><i><a>');
			?>

					<div data-alert class="callout notice secondary"><small><?php echo strtoupper(date('D j M, g:i A', $notice->getStartTime())); ?></small> <?php echo $text; ?></div>
		<?php }
				?>
			</div>
		</div>
		<?php
	} ?>
