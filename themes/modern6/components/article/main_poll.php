				<?php if(($bottom && $poll->getLocation() != 0) || (!$bottom && $poll->getLocation() != 1)): ?>
				<div class="poll-area-<?php echo $poll->getId(); ?>">
					<div class="panel radius poll">
						<div class="poll-form-<?php echo $poll->getId(); ?>">
							<a name="poll-<?php echo $poll->getId(); ?>"></a>
							<h5><?php echo $poll->getQuestion(); ?></h5>
							<?php
								$resp = $poll->getResponses();
								$count = $resp['total'];
								$resps = $resp['answers'];
							?>
							<?php foreach($resps as $resp): ?>
								<?php
									if($count == 0) {
										$percent = 0;
									} else {
										$percent = ($resp['count']/$count);
									}
								?>
								<div>
									<b><?php echo $resp['label']; ?> <?php if(($poll->getHideResults() == TRUE && (!$poll->canUserRespond())) || $poll->getHideResults() == FALSE): ?>(<?php echo $resp['count']; ?>)<?php endif; ?></b>
									<?php if($poll->canUserRespond()): ?>	
										<a href="<?php echo $article->getURL(); ?>?poll=<?php echo $poll->getId(); ?>&amp;option=<?php echo $resp['id']; ?>" data-poll="<?php echo $poll->getId(); ?>" data-article="<?php echo $article->getId(); ?>" data-option="<?php echo $resp['id']; ?>" class="right poll-option"><b>VOTE</b></a>
									<?php endif; ?>
									<?php if(($poll->getHideResults() == TRUE && (!$poll->canUserRespond())) || $poll->getHideResults() == FALSE): ?>
									<br>
									<div class="progress">
										<span class="meter" style="width: <?php echo($percent*100); ?>%"></span>
									</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
							<?php if($poll->getEnded()): ?>
								<b>This poll is now closed</b>
							<?php elseif(!$poll->canUserRespond()): ?>
								<b>Thank you for voting!</b>
							<?php endif; ?>
						</div>
						<div class="poll-spin-<?php echo $poll->getId(); ?>" style="display: none;">
							<img src="<?php echo STANDARD_URL; ?>/img/loading.gif" alt="Loading">
						</div>
					</div>
				</div>
				<?php endif; ?>
