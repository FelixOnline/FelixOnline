<?php
$timing->log('issue archive page');

$header = array(
    'title' => 'Issue Archive - '.'Felix Online'
);

$theme->resources->addCSS(array('archive.less'));

$theme->render('components/header', $header);
?>
<!-- Archive wrapper -->
<div class="container_12 archive">
    <!-- Search -->
    <div id="archivesearchbar" class="grid_12">
        <h3>Search the Felix archive</h3>
        <form method="GET" action="" class="grid_8">
            <input type="text" name="q" size="40" placeholder="Search the archive.." value="<?php echo $query; ?>" id="searchinput" />
            <input type="submit" value="Search" id="searchbuttonfwd" />
        </form>
		<div class="search-count grid_4">
			<p><?php echo count($search_results); ?> results</p>
		</div>
    </div>
    <div class="clear"></div>

    
    <div class="issuecont clearfix">
		<?php if(!empty($search_results)) { ?>
			<?php foreach($search_results as $key => $issue) {
				$theme->render('snippets/issue', array(
					'issue' => $issue
				));
				if(($key + 1) % 6 == 0) { ?>
					<div class="clear"></div>
				<?php } ?>
			<?php } ?>
		<?php } else { ?> 
			<p class="grid_12">No issues found.</p>
		<?php } ?>
    </div>

    <div class="clear"></div>
</div>
<!-- End of archive wrapper -->
<?php $timing->log('end of issue archive');?>
<?php $theme->render('components/footer'); ?>
