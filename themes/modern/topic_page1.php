<?php

$header = array(
	'title' => $topic->getName().' - '.'Felix Online',
	'meta' => '<meta property="og:image" content="<?php echo $topic->getImage()->getURL(); ?>"/>'
);

$theme->render('components/globals/header', $header);

?>
<?php if(count($articles) == 0): ?>
	<div class="row full-width top-row">
		<div class="small-12 large-9 columns">
			<p>There are no articles in this topic yet.</p>
		</div>
		<div class="small-12 large-3 columns">
<?php else: ?>
	<div class="row full-width top-row" data-equalizer="master" data-equalizer-mq="large-up">
		<div class="small-12 large-6 columns">
			<?php
				if(isset($articles[0])) {
					$theme->setHierarchy(array(
						$topic->getSlug()
					));

					$theme->render('components/category/block_large', array(
						'article' => $articles[0],
						'equalizer' => 'master',
						'show_category' => true,
						'headshot' => false
					));
				}
			?>
		</div>
		<div class="small-12 large-6 columns" data-equalizer-watch="master">
			<div class="row">
				<div class="small-12 medium-6 medium-push-6 columns">
<?php endif; ?>
					<?php $theme->render('components/topic/meta_info'); ?>
					<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => $category)); ?>
				</div>
<?php if(count($articles) > 0): ?>
				<div class="small-12 medium-6 medium-pull-6 columns secondary-articles" data-equalizer-watch="master">
					<div data-equalizer="middle">
						<?php
							if(isset($articles[1])) {
								$theme->setHierarchy(array(
									$topic->getSlug()
								));

								$theme->render('components/category/block_normal', array(
									'article' => $articles[1],
									'equalizer' => 'middle',
									'show_category' => true,
									'headshot' => false
								));
							}
						?>
						<?php
							if(isset($articles[2])) {
								$theme->setHierarchy(array(
									$topic->getSlug()
								));

								$theme->render('components/category/block_normal', array(
									'article' => $articles[2],
									'equalizer' => 'middle',
									'show_category' => true,
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
		<div class="small-12 medium-6 large-3 columns">
<?php
	$theme->setHierarchy(array(
		$topic->getSlug()
	));

	$theme->render('components/category/block_normal', array(
		'article' => $articles[$i],
		'equalizer' => 'master2',
		'show_category' => true,
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
				'class' => $topic,
				'pages' => $pages,
				'span' => \FelixOnline\Core\Settings::get('number_of_pages_in_page_list'),
				'type' => 'topic',
				'key' => $topic->getSlug()
			)); ?>
		</div>
	</div>
	<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken('pagination'); ?>">
	<input type="hidden" name="pag-category" id="pag-category" value="1">
	<input type="hidden" name="pag-headshot" id="pag-headshot" value="0">
<?php
$theme->render('components/globals/footer');
?>
