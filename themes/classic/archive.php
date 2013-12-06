<?php
$timing->log('issue archive page');

$header = array(
    'title' => 'Issue Archive - '.'Felix Online'
);

$theme->resources->addCSS(array('archive.less'));

$theme->render('header', $header);
?>
<!-- Archive wrapper -->
<div class="container_12 archive">
    <!-- Search -->
    <div id="archivesearchbar" class="grid_12">
        <h3>Search the Felix archive</h3>
        <form method="get" action="">
            <input type="text" name="q" size="40" placeholder="Search the archive.." id="searchinput" />
            <input type="submit" value="Search" id="searchbuttonfwd" />
        </form>
    </div>
    <div class="clear"></div>
    
    <h3 class="grid_12">Decades</h3>
        <ul class="tabs">
        <?php foreach($decades as $key => $decade) { ?>  
            <li <?php if($decade['selected']) echo 'class="current"'; ?> >
                <?php if($decade['begin']) { ?>
                    <a href="<?php echo STANDARD_URL; ?>issuearchive/decade/<?php echo $decade['begin']; ?>"><?php echo $decade['begin']; ?>-<?php echo $decade['final']; ?></a>
                <?php } else { ?>
                    <a href="<?php echo STANDARD_URL; ?>issuearchive/year/<?php echo $decade['final']; ?>"><?php echo $decade['final']; ?></a>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
    
    <h3 class="grid_12">Years</h3>
    <ul class="tabsyear">
        <?php 
            if(!$currentdecade['begin']) {
                $currentdecade['begin'] = $currentdecade['final']; 
            }
            for($i = $currentdecade['begin']; $i <= $currentdecade['final']; $i++) { ?>
			<li class="<?php if($i == $year) { ?>current<?php } ?>">
                <a href="<?php STANDARD_URL; ?>issuearchive/year/<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php } ?>
    </ul>
    
    <div class="issuecont clearfix">
        <?php if(!empty($issues)) {
			foreach($issues as $key => $issue) {
				$theme->render('snippets/issue', array(
					'issue' => $issue
				));
				if(($key + 1) % 6 == 0) { ?>
					<div class="clear"></div>
				<?php } ?>
			<?php } ?>
		<?php } else { ?> 
			<p class="grid_12">No issues this year.</p>
		<?php } ?>
    </div>
    
    <?php if (!empty($daily)) { ?>
		<div class="grid_12">
			<h2>The Felix Daily 2011</h2>
		</div>

		<div class="issuecont clearfix">
			<?php foreach($daily as $key => $issue) {
				$theme->render('snippets/issue', array(
					'issue' => $issue
				));
				if(($key + 1) % 6 == 0) {?>
					<div class="clear"></div>
				<?php } ?>
			<?php } ?>
		</div>
    <?php } ?>

    <div class="clear"></div>
    
</div>
<!-- End of archive wrapper -->
<?php $timing->log('end of issue archive');?>
<?php $theme->render('footer'); ?>
