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
<?php if(count($articles) == 0): ?>
	<div class="row full-width top-row">
		<div class="small-12 large-9 columns">
			<p>There are no articles in this category yet.</p>
		</div>
		<div class="small-12 large-3 columns">
<?php else: ?>
	<div class="row full-width top-row" data-equalizer="master" data-equalizer-mq="large-up">
		<div class="small-12 large-6 columns">
			<?php
				if(isset($articles[0])) {
					$theme->setHierarchy(array(
						$category->getCat() // category-{cat}.php
					));

					$theme->render('components/category/block_large', array(
						'article' => $articles[0],
						'equalizer' => 'master',
						'show_category' => false,
						'headshot' => false
					));
				}
			?>
		</div>
		<div class="small-12 large-6 columns" data-equalizer-watch="master">
			<div class="row">
				<div class="small-12 medium-6 medium-push-6 columns">
<?php endif; ?>
					<?php $theme->render('components/category/meta_info'); ?>
					<?php $theme->render('components/category/meta_contribute'); ?>
					<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => $category)); ?>
				</div>
<?php if(count($articles) > 0): ?>
				<div class="small-12 medium-6 medium-pull-6 columns secondary-articles" data-equalizer-watch="master">
					<div data-equalizer="middle">
						<?php
							if(isset($articles[1])) {
								$theme->setHierarchy(array(
									$category->getCat() // category-{cat}.php
								));

								$theme->render('components/category/block_normal', array(
									'article' => $articles[1],
									'equalizer' => 'middle',
									'show_category' => false,
									'headshot' => false
								));
							}
						?>
						<?php
							if(isset($articles[2])) {
								$theme->setHierarchy(array(
									$category->getCat() // category-{cat}.php
								));

								$theme->render('components/category/block_normal', array(
									'article' => $articles[2],
									'equalizer' => 'middle',
									'show_category' => false,
									'headshot' => false
								));
							}
						?>
					</div>
				</div>
			</div>
		</div>
<?php endif; ?>
	</div>

	<div class="row full-width secondary-top-row" data-equalizer="master2">
<?php
	for($i = 3; $i < 7; $i++) {
		if(!isset($articles[$i])) { continue; }
?>
		<div class="small-12 medium-6 large-3 columns <?php if($i == count($articles) -1): echo 'end'; endif; ?>">
<?php
	$theme->setHierarchy(array(
		$category->getCat() // category-{cat}.php
	));

	$theme->render('components/category/block_normal', array(
		'article' => $articles[$i],
		'equalizer' => 'master2',
		'show_category' => false,
		'headshot' => false
	));
?>
		</div>
<?php
	}
?>
	</div>
	<div id="month-viewer" data-final-month="">
	</div>

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
