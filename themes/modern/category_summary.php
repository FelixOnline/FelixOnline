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
		</div>
		<div class="small-12 large-4 columns text-right">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => $category)); ?>
		</div>
	</div>
<?php foreach($children as $child): ?>
	<hr class="month-divider">
	<div class="row full-width">
		<div class="small-12 columns">
			<p class="section-date"><a href="<?php echo $child->getURL(); ?>"><?php echo $child->getLabel(); ?></a></p>
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
		$theme->render('components/category/block_date', array(
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
