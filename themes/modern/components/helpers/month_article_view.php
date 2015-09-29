<div id="month-viewer" data-final-month="<?php echo date('F-Y', end($articles)->getDate()); reset($articles); ?>">
	<hr class="month-divider">
	<?php $current_date = date('F Y', $articles[0]->getDate()); ?>
	<div class="row full-width">
	  <div class="small-12 columns">
		<p class="section-date"><?php echo date('F Y', $articles[0]->getDate()); ?></p>
	  </div>
	</div>
	<div class="row full-width date-row" data-equalizer="<?php echo str_replace(' ', '-', $current_date); ?>" id="<?php echo str_replace(' ', '-', $current_date); ?>">
<?php for($i = 0; $i < count($articles); $i++): ?>
<?php
	$article = $articles[$i];

	if(!isset($articles[$i+1]) || date('F Y', $articles[$i+1]->getDate()) != $current_date) {
		$end = ' end';
	} else {
		$end = '';
	}
?>
				<div class="small-12 large-4 date-article columns<?php echo $end; ?>">
<?php
	if($category) {
		$theme->setHierarchy(array(
			$category->getCat() // category-{cat}.php
		));
	}

	$theme->render('components/category/block_date', array(
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

	<hr class="month-divider">
<?php $current_date = date('F Y', $articles[$i+1]->getDate()); ?>
	<div class="row full-width">
		<div class="small-12 columns">
			<p class="section-date"><?php echo date('F Y', $articles[$i+1]->getDate()); ?></p>
		</div>
	</div>
	<div class="row full-width date-row" data-equalizer="<?php echo str_replace(' ', '-', $current_date); ?>" id="<?php echo str_replace(' ', '-', $current_date); ?>">
<?php
	}
?>
<?php endfor; ?>
	</div>
</div>