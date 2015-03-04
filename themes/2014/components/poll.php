				<?php if(($bottom && $poll->getLocation() != 0) || (!$bottom && $poll->getLocation() != 1)): ?>
				<div class="panel radius">
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
								<a href="<?php echo $article->getURL(); ?>?poll=<?php echo $poll->getId(); ?>&amp;option=<?php echo $resp['id']; ?>" class="right"><b>VOTE</b></a>
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
				<?php endif; ?>
