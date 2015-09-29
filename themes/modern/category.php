<?php

$header = array(
	'title' => $category->getLabel().' - '.'Felix Online',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/globals/header', $header);

?>
<?php if($category->getChildren()): ?>
	<div class="row full-width">
		<div class="small-12 columns">
			<?php $theme->render('components/category/block_subnav'); ?>
		</div>
	</div>
<?php endif; ?>
	<div class="row full-width">
		<div class="small-12 large-8 columns breadcrumb-float-top">
			<?php $theme->render('components/helpers/breadcrumbs', array('origin' => $category, 'type' => 'category')); ?>
			<h1 class="breadcrumb-after"><?php echo $category->getLabel(); ?></h1>
			<div class="header-info-icons">
				<a href="mailto:<?php echo $category->getEmail(); ?>"><span class="social social-e-mail"></span>&nbsp;<?php echo $category->getEmail(); ?></a>
<?php if($category->getTwitter()): ?>
				<a href="http://twitter.com/<?php echo $category->getTwitter(); ?>"><span class="social social-twitter"></span>&nbsp;@<?php echo $category->getTwitter(); ?></a>
<?php endif; ?>
			</div>
		</div>
		<div class="small-12 large-4 columns text-right">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => $category)); ?>
		</div>
	</div>
<?php if(count($articles) == 0): ?>
	<div class="row full-width top-row">
		<div class="small-12 large-9 columns">
			<p>There are no articles in this category yet.</p>
		</div>
	</div>
<?php else: ?>
<?php $theme->render('components/helpers/month_article_view', array(
	'articles' => $articles,
	'show_category' => false,
	'headshot' => false
	)); ?>
<?php endif; ?>
	<div class="row full-width">
		<div class="small-12 columns paginator-bit">
			<?php $theme->render('components/helpers/pagination', array(
				'pagenum' => $pagenum,
				'class' => $category,
				'pages' => $pages,
				'span' => \FelixOnline\Core\Settings::get('number_of_pages_in_page_list'),
				'type' => 'category',
				'key' => $category->getCat()
			)); ?>
		</div>
	</div>
	<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken('pagination'); ?>">
	<input type="hidden" name="pag-category" id="pag-category" value="0">
	<input type="hidden" name="pag-headshot" id="pag-headshot" value="0">
<?php
$theme->render('components/globals/footer');
?>