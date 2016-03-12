<?php

$header = array(
	'title' => 'All topics - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);

?>
	<div class="row main-row top-row">
		<div class="small-12 medium-8 columns">
			<h1>All topics</h1>
		</div>
		<div class="small-12 medium-4 columns text-right">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => false)); ?>
		</div>
	</div>
	<div class="row main-row small-up-1 medium-up-4">

<?php
	if(isset($topics)) {
		foreach($topics as $topic):
?>
		<div class="columns">
		<?php
			$theme->render('components/article/topic_block', array('topic' => $topic));
		?>
		</div>
<?php
		endforeach;
	} else {
		echo "<p>No topics found.</p>";
	}
?>
	</div>
<?php
$theme->render('components/globals/footer');
?>
