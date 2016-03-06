<?php

$header = array(
	'title' => $category->getLabel().' - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
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
		</div>
		<div class="small-12 large-4 columns text-right">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => $category)); ?>
		</div>
	</div>
<?php foreach($children as $child): ?>
	<div class="row <?php echo $child->getCat(); ?>">
		<div class="small-12 columns">
			<div class="bar-text"><a href="<?php echo $child->getURL(); ?>"><?php echo $child->getLabel(); ?></a></div>
		</div>
	</div>
	<div class="row full-width" data-equalizer="<?php echo $child->getCat(); ?>">
<?php for($i = 0; $i < count($articles[$child->getCat()]); $i++): ?>
<?php
	if($i > 0 && ($i + 1) % 4 == 0) {
		$end = ' end';
	} else {
		$end = '';
	}
?>
	<div class="small-12 large-3 columns<?php echo $end; ?>">
	<?php
		$theme->render('components/category/block_normal', array(
			'article' => $articles[$child->getCat()][$i],
			'equalizer' => $child->getCat(),
			'show_category' => false,
			'headshot' => false
		));
	?>
	</div>
<?php endfor; ?>
	</div>
<?php endforeach; ?>
<?php
$theme->render('components/globals/footer');
?>
