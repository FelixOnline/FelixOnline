<?php
$timing->log('category page');

$header = array(
	'title' => $category->getLabel().' - '.'Felix Online',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header);
?>
<!-- Section header -->
<div class="container_12 clearfix">
	<div class="grid_12 section_header <?php echo $category->getCat(); ?>">
		<h2><?php echo $category->getLabel(); ?></h2>
		<div id="info">
			<ul>
				<?php if(is_array($category->getEditors())): ?><li class="editors">Editors: <b><?php echo Utility::outputUserList($category->getEditors(), true);?></b></li><?php endif; ?>
				<?php if($category->getEmail()) { ?>
					<li class="email"><?php echo Utility::hideEmail($category->getEmail());?></li>
				<?php } ?>
				<li class="rss"><a href="rss.php?cat=<?php echo $category->getCat();?>" target="_blank">RSS Feed</a></li>
			</ul>
		</div>
	</div>
</div>
<!-- End of section header -->

<!-- Section articles -->
<div class="container_12 section">
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8">
		<?php 
			$theme->render('sidebar/categoryFeaturedBox');
			$theme->render('sidebar/sexsurvey');
			$theme->render('sidebar/mediaBox');
			$theme->render('sidebar/socialLinks');
			$theme->render('sidebar/fbActivity');
			$theme->render('sidebar/mostPopular');
		?>
	</div>
	<!-- End of sidebar -->

	<!-- Category articles -->
	<div class="grid_8 pull_4">
		<?php
			/* First page */
			if($pagenum == 1) { 
				if (count($articles) < 2) {
					?>There are no articles in this category<?php
				}
				foreach($articles as $key => $article) {
					if($key == 0) { // top story 
						$theme->render('snippets/articlelist/article_large', array(
							'article' => $article
						));
					} else if ($key <= 2 && $key > 0) { // middle stories 
						$theme->render('snippets/articlelist/article_medium', array(
							'article' => $article
						));
					} else if ($key >= 3) { // end stories
						$theme->render('snippets/articlelist/article_small', array(
							'article' => $article
						));
					}
				}
			}
			/* Not first page */
			else {
				if (count($articles) == 0) { ?>
					Could not find any articles
			<?php }
				foreach($articles as $article) { ?>
						<div class="featBox">
							<div class="border clearfix">
								<h3>
									<a href="<?php echo $article->getURL();?>">
										<?php echo $article->getTitle();?>
									</a>
								</h3>
								<p>
									<?php echo $article->getPreview(30); ?>
								</p>
								<div id="storyMeta">
									<ul class="metaList">
										<?php if ($article->getCategory()->getCat() == 'comment') { ?>
										<li id="articleAuthor">
											<?php echo Utility::outputUserList($article->getAuthors()); ?>
										</li>
										<?php } ?>
										<?php if($article->getNumComments()) { ?>
											<li id="comments">
												<a href="<?php echo $article->getURL();?>#commentHeader">
													<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
												</a>
											</li>
										<?php } ?>
										<li>
											<?php echo date("l F j, Y",$article->getDate());?>
										</li>
									</ul>
								</div>
							</div>
						</div>
				<?php }
			}
		?>

	<?php $theme->render('snippets/pagination', array(
		'pagenum' => $pagenum,
		'class' => $category,
		'pages' => $pages,
		'span' => NUMBER_OF_PAGES_IN_PAGE_LIST
	)); ?>
	</div>
	<!-- End of category articles -->
</div>
<?php
$timing->log('end of category page');
$theme->render('footer');
?>
