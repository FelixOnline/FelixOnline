<?php

$header = array(
	'title' => $topic->getName().' - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:image" content="'.$topic->getImage()->getUrl().'"/>'
);

$theme->render('components/globals/header', $header);

?>
<div class="row main-row top-row hide-for-large">
	<div class="small-12 columns">
		<?php $theme->render('components/topic/meta_info'); ?>
	</div>
</div>

<?php if(count($articles) == 0): ?>
	<div class="row main-row top-row">
		<div class="small-12 medium-8 columns">
			<p>There are no articles in this topic yet.</p>
		</div>
		<div class="small-12 medium-4 columns">
<?php else: ?>
	<div class="row main-row top-row">
		<div class="small-12 medium-6 columns">
			<?php
				if(isset($articles[0])) {
					$theme->setHierarchy(array(
						$articles[0]->getCategory()->getCat()
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
		<div class="small-12 medium-6 large-3 columns">
			<?php
				if(isset($articles[1])) {
					$theme->setHierarchy(array(
						$articles[1]->getCategory()->getCat() // category-{cat}.php
					));

					$theme->render('components/category/block_normal', array(
						'article' => $articles[1],
						'show_category' => false,
						'headshot' => false
					));
				}
			?>
			<?php
				if(isset($articles[2])) {
					$theme->setHierarchy(array(
						$articles[2]->getCategory()->getCat() // category-{cat}.php
					));

					$theme->render('components/category/block_normal', array(
						'article' => $articles[2],
						'show_category' => false,
						'headshot' => false
					));
				}
			?>
		</div>
		<div class="small-12 large-3 columns">
<?php endif; /* From no articles check */ ?>
			<div class="row">
				<div class="columns small-12 medium-6 large-12 show-for-large">
					<?php $theme->render('components/topic/meta_info'); ?>
				</div>
				<div class="columns small-12 medium-6 large-12">
					<?php $theme->render('components/home/block_contribute'); ?>
				</div>
				<div class="columns small-12 medium-12 large-12">
					<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => false)); ?>
				</div>
			</div>
		</div>
	</div>
<?php if(count($articles) > 0): ?>
				<div class="small-12 large-6 medium-pull-12 columns secondary-articles">

				</div>
			</div>
		</div>
<?php endif; ?>
	</div>

	<div class="row main-row small-up-1 medium-up-2 large-up-4" >
<?php
	for($i = 3; $i < 7; $i++) {
		if(!isset($articles[$i])) { continue; }
?>
		<div class="columns">
<?php
	$theme->setHierarchy(array(
		$articles[0]->getCategory()->getCat()
	));

	$theme->render('components/category/block_normal', array(
		'article' => $articles[$i],
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

<?php if(count($articles) > 0): ?>
	<div class="row main-row">
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
<?php endif; ?>
<?php
$theme->render('components/globals/footer');
?>
