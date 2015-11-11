<?php
$header = array(
	'title' => 'Issue Archive - '.'Felix Online'
);

$theme->render('components/globals/header', $header);
?>

<!-- Archive wrapper -->
<div class="row full-width">
	<div class="medium-5 large-3 columns medium-push-7 large-push-9">
		<?php $theme->render('components/home/block_pdf'); ?>

		<?php $theme->render('components/issuearchive/block_search', array('back' => false)); ?>
		
		<div id="archivebrowser" class="info-box">
			<h3>Browser</h3>
			<ul class="side-nav">
				<?php foreach($decades as $key => $decade) { ?>  
					<li <?php if($decade['selected']) echo 'class="active"'; ?> >
						<?php if($decade['begin'] != $decade['final']) { ?>
							<a href="<?php echo STANDARD_URL; ?>issuearchive/decade/<?php echo $decade['begin']; ?>"><?php echo $decade['begin']; ?>-<?php echo $decade['final']; ?></a>
						<?php } else { ?>
							<a href="<?php echo STANDARD_URL; ?>issuearchive/year/<?php echo $decade['final']; ?>"><?php echo $decade['final']; ?></a>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		</div>

		<?php $theme->render('components/helpers/block_advert', array('sidebar' => true)); ?>
	</div>
	<div class="large-9 large-pull-3 medium-7 medium-pull-5 columns">
		<h1>Issue Archive</h1>
		<p>The issue archive was made possible through kind donations from <a href="http://www.imperialcollegeunion.org/">Imperial College Union</a> and the IC Trust.</p>

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
						<div class="small-12 columns">
							<h1 class="archive-publication-name"><?php echo $PubName; ?></h1>
						</div>
						<br>
						<?php
					}

					foreach($PubIssues as $key => $issue) {
						if(($key + 1) % 4 == 0 || !array_key_exists(($key + 1), $PubIssues)) { $last = true; } else { $last = false; }
						$theme->render('components/issuearchive/issue', array(
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
<?php $theme->render('components/globals/footer'); ?>
