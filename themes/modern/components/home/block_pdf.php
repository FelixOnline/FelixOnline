            <div class="pdf-info-box info-box">
              <h1>Latest Issue</h1>
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
					<a href="<?php echo $issue->getDownloadURL(); ?>" class="thumbLink">
						<img src="<?php echo $issue->getThumbnailURL();?>" alt="<?php echo $issue->getId();?>"/>
					</a>
                </div>
                <div class="small-8 columns">
                  <p><span class="issue-no">Issue <?php echo $issue->getIssue(); ?></span><br><span class="issue-date"><?php echo date("l jS F", $issue->getDate()); ?></span></p>
                  <a href="<?php echo STANDARD_URL.'issuearchive'; ?>" class="button tiny radius">More Issues</a>
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
            </div>
