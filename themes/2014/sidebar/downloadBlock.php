				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>Download the latest <i>Felix</i></h3>
				</div>
				<br>
				<?php
					$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\ArchiveIssue', 'archive_issue');
					$issue = $manager->filter('publication = %i', array(\FelixOnline\Core\Settings::get('frontpage_publication')))
									 ->filter('inactive = 0')
									 ->order('date', 'DESC')
									 ->limit(0, 1)
									 ->values();
					$issue = $issue[0];

					if($issue):
						try {
							// prime issue
							$issue->getThumbnailURL();
				?>
				<div class="row">
				<div class="small-4 columns">
					<center>
					<a href="<?php echo $issue->getDownloadURL(); ?>" class="thumbLink">
						<img src="<?php echo $issue->getThumbnailURL();?>" alt="<?php echo $issue->getId();?>"/>
					</a>
					</center>
				</div>
				<div class="small-8 columns">
					<p>
						<b><?php echo date("l jS F", $issue->getDate()); ?></b>
						<br>Issue <?php echo $issue->getIssue(); ?><br>
						<a href="<?php echo STANDARD_URL.'issuearchive'; ?>">More issues</a>
					</p>
				</div>
				</div>
				<?php
						} catch(\FelixOnline\Exceptions\InternalException $e) {
							echo '<p><b>Sorry, we are having some trouble loading issue '.$issue->getIssue().'. Please try again later.</b></p>';
						}
					else:
						echo '<p>No issues found.</p>';
					endif;
				?>
				<br>