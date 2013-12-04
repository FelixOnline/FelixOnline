<!-- Page list -->
<div class="grid_8 clearfix">
	<ul id="pageList" class="clearfix">
		<li id="desc">Pages:</li>
		<?php if ($pagenum != 1) { // Previous page arrow ?>
			<li class="arrow">
				<a href="<?php echo $class->getURL($pagenum-1); ?>">
					&#171;
				</a>
			</li>
		<?php } 
			if ($pages > 1) {
				if ($pages > $span) { // more pages than limit
					if ($pagenum >= ($span/2)) {
						$start = ($pagenum - $span/2)+1;
						$limit = $pagenum + $span/2;
						if ($limit > $pages) {
							$limit = $pages;
							$start = $limit - $span;
						}
					} else {
						$start = 1;
						$limit = $span;
					}
				} else {
					$limit = $pages;
					$start = 1;
				}
				for ($i=$start;$i<=$limit;$i++) {
					if($pagenum==$i) { ?>
						<li class="selected">
					<?php } else { ?>
						<li>
							<a href="<?php echo $class->getURL($i); ?>">
					<?php } ?>
						<?php echo $i; ?>
					<?php if($pagenum==$i) { ?>
						</li>
					<?php } else { ?>
						</a></li>
					<?php }
				}
			} else { ?>
				<li class="selected">1</li>
			<?php }
			if ($pagenum != $pages) { // Next page arrow ?>
				<li class="arrow">
					<a href="<?php echo $class->getURL($pagenum+1);?>">
						&#187;
					</a>
				</li>
			<?php }
		?>
	</ul>
</div>
<!-- End of page list -->
