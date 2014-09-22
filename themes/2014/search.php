<?php
$timing->log('frontpage');

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header); 
$timing->log('after header');

?>

	<div class="container_12">
		<!-- Sidebar -->
		<div class="sidebar grid_4 push_8">
			<?php 
				$theme->render('sidebar/fbActivity');
				$theme->render('sidebar/mostPopular');
				$theme->render('sidebar/socialLinks');
			?>
		</div>
		<!-- End of sidebar -->
		
		<!--Search container -->
		<div class="grid_8 pull_4">
			<?php if (isset($toofew) && $toofew == true) { ?>
				<p>Uh oh! You did not specify enough search terms. Please try again!</p>
			<?php } else { ?>
				<h2>Search results for "<?php echo $query; ?>" -  <?php echo $article_count; ?> results</h2>
				<?php if ($article_count == 0 && $people_count == 0) { ?>
					<p>Uh oh! We couldn't find what you were looking for. Please try again!</p>
				<?php } else { ?>
					<?php if ($people_count !== 0) { ?>
						<div id="peopleresult">
							<h3>People</h3>
							<ul>
								<?php foreach ($people as $person) { ?>
									<li><a href="user/<?php echo $person['user'];?>/"><?php echo $person['name'];?></a></li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
				
					<?php if ($article_count !== 0) { ?>
						<div id="articleListCont">
							<h3>Articles</h3>
							<?php foreach ($articles as $key => $article) { ?>
								<?php if ($page == 1) {
									if ($key < 4) { ?>
										<div class="userArticle">
											<div class="userArticleDate grid_1 alpha">
												<span><?php echo date('jS',$article->getPublished()); ?></span><br/>
												<?php echo date('F Y',$article->getPublished()); ?><br/>
											</div>
											<div class="userArticleInfo grid_7 omega <?php if ($article->getCategory()->getCat() == 'comment') echo 'second';?>">
												<h3><a href="<?php echo $article->getUrl();?>"><?php echo $article->getTitle();?></a></h3>
												<div class="subHeader">
													<p><?php echo $article->getPreview(30); ?></p>
													<div id="storyMeta">
														<ul class="metaList">
															<li id="category"><a href="<?php echo $article->getCategory()->getLabel();?>" class="<?php echo $article->getCategory()->getCat();?>"><?php echo $article->getCategory()->getLabel();?></a></li>
															<?php if ($article->getCategory()->getCat() == 'comment') { ?>
															<li id="articleAuthor">
																<?php echo Utility::outputUserList($article->getAuthors()); ?>
															</li>
															<?php } ?>
															<li id="comments"><a href="<?php echo $article->getUrl();?>#commentHeader"><?php $num_comments = $article->getNumComments(); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
														</ul>
													</div>
												</div>
												<?php if ($article->getCategory()->getCat() != 'comment') { ?>
													<div id="secondStoryPic">
														<a href="<?php echo $article->getUrl();?>">
															<?php if ($article->getImage()): ?>
																<img id="secondStoryPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(220, 130); ?>" height="130px" width="220px">
															<?php else: ?>
																<img id="secondStoryPhoto" alt="" src="<?php echo IMAGE_URL.'/220/130/'.DEFAULT_IMG_URI; ?>" height="130px" width="220px">
															<?php endif; ?>
														</a>
													</div>
												<?php } ?>
												<div class="clear"></div>
											</div>
											<div class="clear"></div>
										</div>
									<?php } else { ?>
										<div class="userArticle">
											<div class="userArticleDate grid_1 alpha">
												<span><?php echo date('jS',$article->getPublished()); ?></span><br/>
												<?php echo date('F Y',$article->getPublished()); ?><br/>
											</div>
											<div class="userArticleInfo grid_7 omega second">
												<h3><a href="<?php echo $article->getUrl();?>"><?php echo $article->getTitle();?></a></h3>
												<div class="subHeader">
													<p><?php echo $article->getPreview(30); ?></p>
													<div id="storyMeta">
														<ul class="metaList">
															<li id="category"><a href="<?php echo $article->getCategory()->getLabel();?>" class="<?php echo $article->getCategory()->getCat();?>"><?php echo $article->getCategory()->getLabel();?></a></li>
															<?php if ($article->getCategory()->getCat() == 'comment') { ?>
															<li id="articleAuthor">
																<?php echo Utility::outputUserList($article->getAuthors()); ?>
															</li>
															<?php } ?>
															<li id="comments"><a href="<?php echo $article->getUrl();?>#commentHeader"><?php $num_comments = $article->getNumComments(); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
														</ul>
													</div>
												</div>
												<div class="clear"></div>
											</div>
											<div class="clear"></div>
										</div>
									<?php } ?>
								<?php } else { ?>
									<div class="userArticle">
										<div class="userArticleDate grid_1 alpha">
											<span><?php echo date('jS',$article->getPublished()); ?></span><br/>
											<?php echo date('F Y',$article->getPublished()); ?><br/>
										</div>
										<div class="userArticleInfo grid_7 omega second">
											<h3><a href="<?php echo $article->getUrl();?>"><?php echo $article->getTitle();?></a></h3>
											<div class="subHeader">
												<p><?php echo $article->getPreview(30); ?></p>
												<div id="storyMeta">
													<ul class="metaList">
														<li id="category"><a href="<?php echo $article->getCategory()->getLabel();?>" class="<?php echo $article->getCategory()->getCat();?>"><?php echo $article->getCategory()->getLabel();?></a></li>
														<?php if ($article->getCategory()->getCat() == 'comment') { ?>
														<li id="articleAuthor">
															<?php echo Utility::outputUserList($article->getAuthors()); ?>
														</li>
														<?php } ?>
														<li id="comments"><a href="<?php echo $article->getUrl();?>#commentHeader"><?php $num_comments = $article->getNumComments(); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
													</ul>
												</div>
											</div>

											<div class="clear"></div>
										</div>
										<div class="clear"></div>
									</div>
								<?php } ?>
							<?php } ?>
						</div>

						<!-- Page list -->
						<div class="grid_8 clearfix">
							<ul id="pageList" class="clearfix">
								<li id="desc">Pages:</li>
								<?php if ($page != 1) // Previous page arrow
										echo '<li class="arrow"><a href="search/?q='.$query.'&p='.($page-1).'">&#171;</a></li>';
												
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
											echo (($page==$i)?'<li class="selected">':('<li><a href="search/?q='.$query.'&p='.$i.'">')).$i.(($page==$i)?'</li>':'</a></li>');
									} else {
										echo '<li class="selected">1</li>';
									}
									if ($page != $pages) // Next page arrow
										echo '<li class="arrow"><a href="search/?q='.$query.'&p='.($page+1).'">&#187;</a></li>';
								?>
							</ul>
						</div>
						<div class="clear"></div>
					<?php } ?> 
				<?php } ?>
		<?php } ?>
		</div>
		<!-- End of search container -->
		<div class="clear"></div>
	</div>
	
<?php $timing->log('end of search');?>
<?php $theme->render('components/footer'); ?>
