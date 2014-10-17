<?php
$timing->log('category page');

$header = array(
	'title' => $category->getLabel().' - '.'Felix Online',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header);
?>
<!-- Section header -->
		<div class="section-title section-title-<?php echo $category->getCat(); ?>">
			<div class="row">
				<div class="medium-6 columns">
					<h1><?php echo $category->getLabel(); ?></h1>
				</div>
				<div class="medium-6 columns">
					<div class="section-editors text-right show-for-medium-up">Edited by<br><b><?php echo Utility::outputUserList($category->getEditors(), true);?></b></div>
				</div>
			</div>
		</div>
<!-- End of section header -->

	<!-- Category articles -->
		<div class="row">
			<div class="medium-8 columns">
			<?php
				/* First page */
				if($pagenum == 1) { 
					if (count($articles) == 0) {
						?>There are no articles in this category.<?php
					} else {
						foreach($articles as $key => $article) {
							if($key == 0) { // top story 
								$theme->render('components/articlelist/article_large', array(
									'article' => $article,
									'show_authors' => true
								));
							} else { // middle stories 
								$theme->render('components/articlelist/article_medium', array(
									'article' => $article,
									'show_authors' => true
								));
							}
						}
					}
				} else {
					/* Not first page */
					if (count($articles) == 0) {
						 ?> Could not find any articles.<?php
					} else {
						foreach($articles as $article) {
							$theme->render('components/articlelist/article_medium', array(
								'article' => $article,
								'show_authors' => true
							));
						}
					}
				}
			?>

			<?php $theme->render('components/pagination', array(
				'pagenum' => $pagenum,
				'class' => $category,
				'pages' => $pages,
				'span' => NUMBER_OF_PAGES_IN_PAGE_LIST
			)); ?>
			</div>
			<div class="medium-4 columns">
				<?php $theme->render('sidebar/contactSection', array('section' => $section)); ?>

				<?php $theme->render('sidebar/contributionPolicy', array('section' => $section)); ?>

				<?php $theme->render('sidebar/mostPopular'); ?>

				<?php $theme->render('sidebar/twitter'); ?>
			</div>
		</div>
	<!-- End of category articles -->

<?php
$timing->log('end of category page');
$theme->render('components/footer');
?>
