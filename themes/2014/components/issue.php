<a href="<?php echo $issue->getDownloadURL(); ?>" class="thumbLink">
	<div class="medium-3 columns thumb<?php if($last): ?> end <?php endif; ?>">
		<div class="panel">
			<div class="issue">
				<?php echo $issue->getIssueNo(); ?>
			</div>
			<center><img src="<?php echo $issue->getThumbnailURL();?>" alt="<?php echo $issue->getId();?>"/></center>
			<div class="date">
				<?php echo date("l jS F", $issue->getPubDate()); ?>
			</div>
			<?php if ($issue->hasRelevance()) { ?>
				<div class="relevance">
					Relevance: <?php echo sprintf("%.2f", $issue->getRelevance()); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</a>
