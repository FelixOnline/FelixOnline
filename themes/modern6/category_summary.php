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
		<div class="small-12 medium-8 columns ">
			<h1 class="breadcrumb-after"><?php echo $category->getLabel(); ?></h1>
		</div>
		<div class="small-12 medium-4 columns text-right">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => $category)); ?>
		</div>
	</div>
<?php foreach($children as $child): ?>
	<div class="row main-row <?php echo $child->getCat(); ?>">
		<div class="small-12 columns">
			<div class="bar-text"><a href="<?php echo $child->getURL(); ?>"><?php echo $child->getLabel(); ?></a></div>
		</div>
	</div>
	<div class="row small-up-1 medium-up-4 main-row top-row">
<?php foreach($articles[$child->getCat()] as $article): ?>
	<div class="columns">
	<?php
		$theme->render('components/category/block_normal', array(
			'article' => $article,
			'show_category' => false,
			'headshot' => false
		));
	?>
	</div>
<?php endforeach; ?>
	</div>
<?php endforeach; ?>
<?php
$theme->render('components/globals/footer');
?>
