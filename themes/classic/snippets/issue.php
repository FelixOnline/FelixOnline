<a href="<?php echo $issue->getDownloadURL(); ?>" class="thumbLink">
	<div class="thumb grid_2">
		<div class="issue">
			<?php echo $issue->getIssueNo(); ?>
		</div>
		<img src="<?php echo $issue->getThumbnailURL();?>" alt="<?php echo $issue->getId();?>"/>
		<div class="date">
			<?php echo date("l jS F", $issue->getPubDate()); ?>
		</div>
		<?php if ($issue->hasRelevance()) { ?>
			<div class="relevance">
				Relevance: <?php echo sprintf("%.2f", $issue->getRelevance()); ?>
			</div>
		<?php } ?>
	</div>
</a>
