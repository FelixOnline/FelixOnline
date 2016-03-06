<?php
$header = array(
	'title' => 'Issue Archive - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => '	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>'
);

$theme->render('components/globals/header', $header);
?>

<!-- Archive wrapper -->
<div class="row top-row main-row">
	<div class="medium-5 large-3 columns medium-push-7 large-push-9">
		<?php $theme->render('components/home/block_pdf'); ?>

		<?php $theme->render('components/issuearchive/block_search', array('back' => false)); ?>
		
		<div id="archivebrowser" class="info-box info-title-only">
			<h3>Browser</h3>
		</div>
		<div class="info-secondary-box pad">
			<ul class="menu vertical side-menu">
				<?php foreach($decades as $key => $decade) { ?>  
					<li <?php if($decade['selected']) echo 'class="is-active"'; ?> >
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

		<ul class="tabs archive-years">
			<?php 
				for($i = $currentdecade['begin']; $i <= $currentdecade['final']; $i++) { ?>
				<li class="tabs-title <?php if($i == $year) { ?>is-active<?php } ?>">
					<a href="<?php STANDARD_URL; ?>issuearchive/year/<?php echo $i; ?>"><?php echo $i; ?></a>
				</li>
			<?php } ?>
		</ul>
		<br>
		<div class="row small-up-2 medium-up-3 large-up-4 issuecont">
			<?php foreach($issues as $PubId => $DataArray) {
				$PubName = $DataArray[0];
				$PubIssues = $DataArray[1];
				
				if(!empty($PubIssues)) {
					if($PubId != 1) {
						?>
						</div>
						<div class="row felix_default">
							<div class="small-12 columns">
								<div class="bar-text"><?php echo $PubName; ?></div>
							</div>
						</div>
						<div class="row top-row small-up-2 medium-up-3 large-up-4 issuecont">
						<?php
					}

					foreach($PubIssues as $key => $issue) {
						$theme->render('components/issuearchive/issue', array(
							'issue' => $issue
						));
					}
				} elseif($PubId == 1) { ?>
					</div>
					<div class="row">
						<div class="small-12 columns"><div class="callout alert">No issues in the selected year.</div></div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>    
</div>
<!-- End of archive wrapper -->
<?php $theme->render('components/globals/footer'); ?>
