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

<?php if($category->getSecret()): ?>
	<div class="row full-width top-row">
		<div class="small-12 columns">
			<div class="alert-box notice secondary"><b>Looking for this at home?</b> You can only access <?php echo $category->getLabel(); ?> from the College network or the VPN.</div>
		</div>
	</div>
<?php endif; ?>

<?php foreach($children as $child): ?>
	<hr class="month-divider <?php echo $child->getCat(); ?>">
	<div class="row full-width">
		<div class="small-12 columns">
			<p class="section-date <?php echo $child->getCat(); ?>"><a href="<?php echo $child->getURL(); ?>"><?php echo $child->getLabel(); ?></a></p>
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
