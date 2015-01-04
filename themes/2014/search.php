<?php
$timing->log('frontpage');

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header); 
$timing->log('after header');

?>
	<div class="row felix-pad-top">
		<div class="medium-8 columns">
			<?php if (isset($toofew) && $toofew == true) { ?>
				<div class="alert-box">Uh oh! You did not specify enough search terms. Please try again!</div>
			<?php } else { ?>
				<div class="alert-box"><b>You searched for "<?php echo $query; ?>" and got <?php echo $article_count; ?> results.</b></div>
				<?php if ($article_count == 0 && $people_count == 0) { ?>
					<div class="alert-box">Uh oh! We couldn't find what you were looking for. Please try again!</div>
				<?php } else { ?>
					<?php if ($article_count !== 0) { ?>
						<div class="felix-item-title felix-item-title felix-item-title-generic">
							<h2>Articles</h2>
						</div>
						<?php foreach ($articles as $key => $article) { ?>
							<?php
							$theme->render('components/articlelist/article_medium', array(
								'article' => $article,
								'show_authors' => true
							));
							?>
						<?php } ?>
						<!-- Page list -->
						<div class="pagination-centered">
							<ul class="pagination" role="menubar" aria-label="Pagination">
								<?php if ($page != 1) { // Previous page arrow ?>
									<li class="arrow">
										<?php echo '<a href="search/?q='.$query.'&p='.($page-1).'">'; ?>
											&laquo; Previous
										</a>
									</li>
								<?php } else { ?>
									 <li class="arrow unavailable" aria-disabled="true"><a>&laquo; Previous</a></li>
								<?php }		
									$pages = ceil(($article_count - ARTICLES_PER_USER_PAGE)/ARTICLES_PER_USER_PAGE) + 1;
									if ($pages>1) {
										$span = NUMBER_OF_PAGES_IN_PAGE_LIST;
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
											echo (($page==$i)?'<li class="current">':('<li>')).'<a href="search/?q='.$query.'&p='.$i.'">'.$i.'</a></li>';
									} else {
										echo '<li class="current">1</li>';
									}
									if ($page != $pages) { // Next page arrow ?>
										<li class="arrow">
											<?php echo '<a href="search/?q='.$query.'&p='.($page+1).'">'; ?>
												Next &raquo;
											</a>
										</li>
									<?php } else { ?>
										<li class="arrow unavailable" aria-disabled="true"><a>Next &raquo;</a></li>
									<?php }
								?>
							</ul>
						</div>
					<?php } else { ?>
						<div class="alert-box">No articles were found, but we did find some people - check the sidebar.</div>
					<?php } ?>
				<?php } ?> 
			<?php } ?>
		</div>
		<div class="medium-4 columns">
			<?php if (isset($people_count) && $people_count !== 0) { ?>
				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>People</h3>
				</div>
				<ul class="search-people-list">
					<?php foreach ($people as $person) { ?>
						<li><a href="user/<?php echo $person['user'];?>/"><?php echo $person['name'];?></a></li>
					<?php } ?>
				</ul>
			<?php } ?>

			<?php $theme->render('sidebar/contributionPolicy'); ?>

			<?php $theme->render('sidebar/mostPopular'); ?>

			<?php $theme->render('sidebar/twitter'); ?>
		</div>
		<!-- End of search container -->
	</div>
	
<?php $timing->log('end of search');?>
<?php $theme->render('components/footer'); ?>
