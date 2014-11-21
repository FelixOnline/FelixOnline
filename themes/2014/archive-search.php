<?php
$timing->log('issue archive page');

$header = array(
	'title' => 'Issue Archive - '.'Felix Online'
);

$theme->resources->addCSS(array('archive.less'));

$theme->render('components/header', $header);
?>
		<div class="archive-title">
			<div class="row">
				<div class="medium-12 columns">
					<h1>Issue Archive</h1>
				</div>
			</div>
		</div>
<!-- Archive wrapper -->
<div class="row">
	<div class="medium-3 columns medium-push-9">
		<!-- Search -->
		<div id="archivesearchbar" class="medium">
			<div class="felix-item-title felix-item-title felix-item-title-generic">
				<h3>Archive Search</h3>
			</div>
			<br>
			<form method="get" action="">
				<input type="text" name="q" size="40" placeholder="Type your query and press enter..." id="searchinput" />
			</form>
			<a href="<?php STANDARD_URL; ?>issuearchive" class="button small">Back to Issue Archive</a>
		</div>
	</div>
	<div class="medium-9 medium-pull-3 columns">
		<div class="felix-item-title felix-item-title felix-item-title-generic">
			<h2>Search Results (<?php echo count($search_results); ?>)</h2>
		</div>
		<br>
		<div class="row issuecont">
			<?php if(!empty($search_results)) {
				foreach($search_results as $key => $issue) {
					if(($key + 1) % 4 == 0) { $last = true;	} else { $last = false; }
					$theme->render('components/issue', array(
						'issue' => $issue,
						'last' => $last
					));
					if($last): ?></div><div class="row issuecont"><?php endif;
				}
			} else { ?> 
				<div class="medium-12 columns"><div class="alert-box">No issues found.</div></div>
			<?php } ?>
		</div>
	</div>    
</div>
<!-- End of archive wrapper -->
<?php $timing->log('end of issue archive');?>
<?php $theme->render('components/footer'); ?>
