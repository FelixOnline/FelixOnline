<div id="month-viewer" data-final-month="<?php echo date('F-Y', end($articles)->getDate()); reset($articles); ?>">
	<?php $current_date = date('F Y', $articles[0]->getDate()); ?>
	<div class="row <?php if($category): echo $category->getCat(); else: echo 'felix_default'; endif; ?>">
		<div class="small-12 columns">
			<div class="bar-text"><?php echo date('F Y', $articles[0]->getDate()); ?></div>
		</div>
	</div>

	<div class="row main-row top-row small-up-1 medium-up-2 large-up-3" id="<?php echo str_replace(' ', '-', $current_date); ?>">
<?php
	for($i = 0; $i < count($articles); $i++):
		$article = $articles[$i];
?>
		<div class="date-article columns">
<?php
	if($category) {
		$theme->setHierarchy(array(
			$category->getCat() // category-{cat}.php
		));
	}

	$theme->render('components/category/block_normal', array(
		'article' => $articles[$i],
		'equalizer' => str_replace(' ', '-', $current_date),
		'show_category' => $show_category,
		'headshot' => $headshot
	));
?>
	</div>

<?php
	if(isset($articles[$i+1]) && date('F Y', $articles[$i+1]->getDate()) != $current_date) {
?>
	</div>

	<?php
		$current_date = date('F Y', $articles[$i+1]->getDate());

		if($category && $category->getCat()) {
			$colour_key = $category->getCat();
		} else {
			$colour_key = 'felix_default';
		}
	?>
	<div class="row <?php echo $colour_key ?>">
		<div class="small-12 columns">
			<div class="bar-text"><?php echo date('F Y', $articles[$i+1]->getDate()); ?></div>
		</div>
	</div>

	<div class="row date-row top-row small-up-1 medium-up-2 large-up-3" id="<?php echo str_replace(' ', '-', $current_date); ?>">
<?php
	}
?>

<?php
	endfor;
?>
	</div>
</div>