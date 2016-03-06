						<!-- Page list -->
						<div class="text-center">
							<ul class="pagination" role="menubar" aria-label="Pagination">
								<?php if ($page != 1) { // Previous page arrow ?>
									<li class="arrow">
										<?php echo '<a href="search/?q='.$query.'&p='.($page-1).'" data-page="'.($page-1).'" data-type="search" data-key="'.$query.'">'; ?>
											&laquo; Previous
										</a>
									</li>
								<?php } else { ?>
									 <li class="arrow unavailable" aria-disabled="true"><a>&laquo; Previous</a></li>
								<?php }		
									$pages = ceil(($article_count - \FelixOnline\Core\Settings::get('articles_per_search_page'))/\FelixOnline\Core\Settings::get('articles_per_search_page')) + 1;
									if ($pages>1) {
										$span = \FelixOnline\Core\Settings::get('number_of_pages_in_page_list');
										if ($pages > $span) {
											if ($page >= ($span/2)) {
												$start = ($page - $span/2)+1;
												$limit = $page + $span/2;
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
										for ($i=$start;$i<=$limit;$i++)
											echo (($page==$i)?'<li class="current">':('<li>')).'<a href="search/?q='.$query.'&p='.$i.'" data-page="'.($i).'" data-type="search" data-key="'.$query.'">'.$i.'</a></li>';
									} else {
										echo '<li class="current">1</li>';
									}
									if ($page != $pages) { // Next page arrow ?>
										<li class="arrow">
											<?php echo '<a href="search/?q='.$query.'&p='.($page+1).'" data-page="'.($page+1).'" class="next" data-type="search" data-key="'.$query.'">'; ?>
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