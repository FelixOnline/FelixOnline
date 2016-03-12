<?php

$header = array(
	'title' => 'All topics - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);

?>
	<div class="row full-width">
		<div class="small-12 medium-8 columns">
			<h1>All topics</h1>
		</div>
		<div class="small-12 medium-4 columns text-right">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => false, 'section' => false)); ?>
		</div>
	</div>
	<div class="row full-width">

<?php
	if(isset($topics)) {
		$i = 0;
		foreach($topics as $topic):
			$i++;

			if($i == 4 || $i == count($topics)) {
				$end = "end";
				$i = 0;
			} else {
				$end = "";
			}
?>
		<div class="small-12 medium-3 columns <?php echo $end; ?>">
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
