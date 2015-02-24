				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>Download the latest <i>Felix</i></h3>
				</div>
				<br>
				<?php
					$link = new ArchiveLink();
					$issue = $link->getLatestForPublication(1);

					if($issue):
						try {
							// prime issue
							$issue->getThumbnailURL();
				?>
				<div class="row">
				<div class="small-4 columns">
					<center>
					<a href="<a href="<?php echo $issue->getDownloadURL(); ?>" class="thumbLink">
						<img src="<?php echo $issue->getThumbnailURL();?>" alt="<?php echo $issue->getId();?>"/>
					</a>
					</center>
				</div>
				<div class="small-8 columns">
					<p>
						<b><?php echo date("l jS F", strtotime($issue->getPubDate())); ?></b>
						<br>Issue <?php echo $issue->getIssueNo(); ?><br>
						<a href="<?php echo STANDARD_URL.'/archive'; ?>">More issues</a>
					</p>
				</div>
				</div>
				<?php
						} catch(\FelixOnline\Exceptions\InternalException $e) {
							echo '<p><b>Sorry, we are having some trouble loading this issue '.$issue->getIssueNo().'. Please try again later.</b></p>';
						}
					else:
						echo '<p>No issues found.</p>';
					endif;
				?>