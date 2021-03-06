<?php

$header = array(
	'title' => $topic->getName().' - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:image" content="'.$topic->getImage()->getUrl().'"/>'
);

$theme->render('components/globals/header', $header);

?>
<div class="row main-row top-row">
	<div class="small-12 large-8 columns">
		<h1><img src="<?php echo $topic->getImage()->getUrl(400,400); ?>" class="headshot" alt=""><?php echo $topic->getName(); ?></h1>
	</div>
	<div class="small-12 large-4 columns text-right">
		<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => false)); ?>
	</div>
</div>

<?php if(count($articles) == 0): ?>
	<div class="row main-row">
		<div class="small-12 columns">
			<p>There are no articles in this topic yet.</p>
		</div>
	</div>
<?php else: ?>
<?php $theme->render('components/helpers/month_article_view', array(
	'articles' => $articles,
	'show_category' => true,
	'headshot' => false
	)); ?>
<?php endif; ?>
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
