<!-- Notices -->
	<?php
	$notices = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Notice', 'notices');
	$notices->filter('hidden = 0')
		->filter('end_time > NOW()')
		->filter('start_time < NOW()');

	$converter = new \Sioen\Converter();

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
			<?php
				$text = preg_replace('/<p[^>]*><\\/p[^>]*>/i', '', $converter->toHTML($notice->getContent()));
				$text = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $text); // Some <p>^B</p> tags can get through some times
				$text = strip_tags($text, '<b><i><u><a><p>'); // Shrink down the valid tag list
				$text = preg_replace("/<p[^>]*?>/", "", $text);
				$text = str_replace("</p>", "<br />", $text); // Replace p with br as p is messy in the notice area
			?>

					<div data-alert class="alert-box notice"><small><?php echo strtoupper(date('D j M, g:i A', $notice->getStartTime())); ?></small> <?php echo $text; ?></div>
		<?php }
				?>
			</div>
		</div>
		<?php
	} ?>
