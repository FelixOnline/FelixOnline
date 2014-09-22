<!-- Page list -->
<div class="pagination-centered">
	<ul class="pagination" role="menubar" aria-label="Pagination">
		<?php if ($pagenum != 1) { // Previous page arrow ?>
			<li class="arrow">
				<a href="<?php echo $class->getURL($pagenum-1); ?>">
					&laquo; Previous
				</a>
			</li>
		<?php } else { ?>
			 <li class="arrow unavailable" aria-disabled="true"><a href="">&laquo; Previous</a></li>
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
						<a href="<?php echo $class->getURL($i); ?>"><?php echo $i; ?></a>
					</li>
					<?php
				}
			} else { ?>
				<li class="current"><a>1</a></li>
			<?php }
			if ($pagenum != $pages) { // Next page arrow ?>
				<li class="arrow">
					<a href="<?php echo $class->getURL($pagenum+1);?>">
						Next &raquo;
					</a>
				</li>
			<?php } else { ?>
				<li class="arrow unavailable" aria-disabled="true"><a href="">Next &raquo;</a></li>
			<?php }
		?>
	</ul>
</div>
<!-- End of page list -->
