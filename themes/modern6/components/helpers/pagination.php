<!-- Page list -->
<div class="text-center">
	<ul class="pagination" role="menubar" aria-label="Pagination">
		<?php if ($pagenum != 1) { // Previous page arrow ?>
			<li class="arrow">
				<a href="<?php echo $class->getURL($pagenum-1); ?>" data-page="<?php echo $pagenum-1; ?>" data-type="<?php echo $type; ?>" data-key="<?php echo $key; ?>">
					&laquo; Previous
				</a>
			</li>
		<?php } else { ?>
			 <li class="arrow unavailable" aria-disabled="true"><a>&laquo; Previous</a></li>
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
					?>
					<li<?php if($pagenum==$i) { ?> class="current"<?php } ?>>
						<a href="<?php echo $class->getURL($i); ?>" data-page="<?php echo $i; ?>" data-type="<?php echo $type; ?>" data-key="<?php echo $key; ?>"><?php echo $i; ?></a>
					</li>
					<?php
				}
			} else { ?>
				<li class="current"><a>1</a></li>
			<?php }
			if ($pagenum != $pages) { // Next page arrow ?>
				<li class="arrow">
					<a href="<?php echo $class->getURL($pagenum+1);?>" data-page="<?php echo $pagenum+1; ?>" class="next" data-type="<?php echo $type; ?>" data-key="<?php echo $key; ?>">
						Next &raquo;
					</a>
				</li>
			<?php } else { ?>
				<li class="arrow unavailable" aria-disabled="true"><a>Next &raquo;</a></li>
			<?php }
		?>
	</ul>
	<div class="pagination-spin" style="display: none;">
		<img src="<?php echo STANDARD_URL; ?>/img/loading.gif" alt="Loading">
	</div>
</div>
<!-- End of page list -->
