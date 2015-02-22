<?php
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
		</div>
		
		<div class="felix-item-title felix-item-title felix-item-title-generic">
			<h3>Browser</h3>
		</div>
		<dl class="tabs">
			<?php foreach($decades as $key => $decade) { ?>  
				<dd <?php if($decade['selected']) echo 'class="active"'; ?> >
					<?php if($decade['begin'] != $decade['final']) { ?>
						<a href="<?php echo STANDARD_URL; ?>issuearchive/decade/<?php echo $decade['begin']; ?>"><?php echo $decade['begin']; ?>-<?php echo $decade['final']; ?></a>
					<?php } else { ?>
						<a href="<?php echo STANDARD_URL; ?>issuearchive/year/<?php echo $decade['final']; ?>"><?php echo $decade['final']; ?></a>
					<?php } ?>
				</dd>
			<?php } ?>
		</dl>
	</div>
	<div class="medium-9 medium-pull-3 columns">
		<dl class="tabs archive-years">
			<?php 
				for($i = $currentdecade['begin']; $i <= $currentdecade['final']; $i++) { ?>
				<dd class="<?php if($i == $year) { ?>active<?php } ?>">
					<a href="<?php STANDARD_URL; ?>issuearchive/year/<?php echo $i; ?>"><?php echo $i; ?></a>
				</dd>
			<?php } ?>
		</dl>
		<br>
		<div class="row issuecont">
			<?php foreach($issues as $PubId => $DataArray) {
				$PubName = $DataArray[0];
				$PubIssues = $DataArray[1];
				
				if(!empty($PubIssues)) {
					if($PubId != 1) {
						?>
						<div class="felix-item-title felix-item-title felix-item-title-generic">
							<h2><?php echo $PubName; ?></h2>
						</div>
						<br>
						<?php
					}

					foreach($PubIssues as $key => $issue) {
						if(($key + 1) % 4 == 0 || !array_key_exists(($key + 1), $PubIssues)) { $last = true; } else { $last = false; }
						$theme->render('components/issue', array(
							'issue' => $issue,
							'last' => $last
						));
						if($last): ?></div><div class="row issuecont"><?php endif;
					}
				} elseif($PubId == 1) { ?> 
					<div class="medium-12 columns"><div class="alert-box">No issues this year.</div></div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>    
</div>
<!-- End of archive wrapper -->
<?php $theme->render('components/footer'); ?>
