<?php

$header = array(
	'title' => $category->getLabel().' - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);

?>

	<div class="row main-row top-row">
		<div class="small-12 large-8 columns">
			<h1><?php echo $category->getLabel(); ?></h1>
<?php if($category->getTwitter() || $category->getEmail()): ?>
			<div class="header-info-icons">
<?php if($category->getEmail()): ?>
				<a href="mailto:<?php echo $category->getEmail(); ?>"><span class="social social-e-mail"></span>&nbsp;<?php echo $category->getEmail(); ?></a>
<?php endif; ?>
<?php if($category->getTwitter()): ?>
				<a href="http://twitter.com/<?php echo $category->getTwitter(); ?>"><span class="social social-twitter"></span>&nbsp;@<?php echo $category->getTwitter(); ?></a>
<?php endif; ?>
			</div>
<?php endif; ?>
		</div>
		<div class="small-12 large-4 columns text-right">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => $category)); ?>
		</div>
	</div>

<?php if($category->getSecret()): ?>
	<div class="row main-row top-row">
		<div class="small-12 columns">
			<div class="callout alert"><b>Looking for this at home?</b> You can only access <?php echo $category->getLabel(); ?> from the College network or the VPN.</div>
		</div>
	</div>
<?php endif; ?>
	
<?php if(count($articles) == 0): ?>
	<div class="row main-row">
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
<?php if(count($articles) > 0): ?>
	<div class="row main-row">
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
<?php endif; ?>
<?php
$theme->render('components/globals/footer');
?>
