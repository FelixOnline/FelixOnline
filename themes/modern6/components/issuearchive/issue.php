<?php
// Prime issue
try {
	$issue->getPrimaryFile(); // Will exception out if there is a problem
?>

	<div class="columns">
		<a href="<?php echo $issue->getDownloadURL(); ?>" class="thumbLink">
			<div class="info-secondary-box pad thumb">
				<div class="issue">
					<?php echo $issue->getIssue(); ?>
				</div>
				<center><img src="<?php echo $issue->getThumbnailURL();?>" alt="<?php echo $issue->getIssue();?>"/></center>
				<div class="date">
					<?php echo date("l jS F", $issue->getDate()); ?>
				</div>
				<?php if ($issue->hasRelevance()) { ?>
					<div class="relevance">
						Relevance: <?php echo sprintf("%.2f", $issue->getRelevance()); ?>%
					</div>
					<div class="source">
						Found in: <?php echo $issue->getPublication()->getName(); ?>
					</div>
				<?php } ?>
			</div>
		</a>
	</div>
<?php
} catch(\FelixOnline\Exceptions\InternalException $e) {
	echo '<div class="small-6 large-3 columns thumb<?php if($last): ?> end <?php endif; ?>">
		<div class="panel issue-panel">
		<p><b>Sorry, we are having some trouble loading issue '.$issue->getIssue().' ('.$issue->getId().'). Please try again later.</b></p>
		</div>
		</div>';
}
?>