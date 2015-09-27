<?php
$header = array(
	'title' => $category->getLabel().' - '.'Felix Online',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header);

$theme->render('components/noticeBlock', array('no_frontpage_only' => true));

?>
<!-- Section header -->
		<div class="section-title section-title-<?php echo $category->getCat(); ?>">
			<div class="row">
				<div class="small-9 columns">
					<h1><?php echo $category->getLabel(); ?></h1>
				</div>
				<div class="small-3 columns">
					<div class="text-right"><a href="<?php echo STANDARD_URL.'rss/'.$category->getCat(); ?>"><img src="<?php echo STANDARD_URL.'themes/2014/'; ?>img/rss.png"></a></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns">
				<div class="section-bar section-title-<?php echo $category->getCat(); ?>"></div>
			</div>
		</div>
<!-- End of section header -->

	<!-- Category articles -->
		<div class="row">
			<div class="medium-8 columns">
				<div class="pagination-content">
					<?php
						$theme->setHierarchy(array(
							$category->getCat() // category_page-{cat}.php
						));

						$theme->render('components/category_page');
					?>

					<?php $theme->render('components/pagination', array(
						'pagenum' => $pagenum,
						'class' => $category,
						'pages' => $pages,
						'span' => \FelixOnline\Core\Settings::get('number_of_pages_in_page_list'),
						'type' => 'category',
						'key' => $category->getCat()
					)); ?>
				</div>
			</div>
			<div class="medium-4 columns">
				<?php $theme->render('sidebar/contactSection', array('section' => $category)); ?>

				<?php $theme->render('components/advert', array('sidebar' => true)); ?>

				<?php $theme->render('sidebar/contributionPolicy', array('section' => $category)); ?>

				<?php $theme->render('sidebar/mostPopular'); ?>

				<?php $theme->render('sidebar/twitter'); ?>
			</div>
		</div>
		<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken('pagination'); ?>">

	<!-- End of category articles -->

<?php
$theme->render('components/footer');
?>
