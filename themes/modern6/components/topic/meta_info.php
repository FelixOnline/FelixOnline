				<div class="topic-info-box info-box info-always-margin">
					<div class="topic-picture" style="background-image: url('<?php echo $topic->getImage()->getUrl(); ?>');"></div>
					<div class="topic-info">
						<h1><?php echo $topic->getName(); ?></h1>
						<?php echo '<p>'.$topic->getText().'</p>'; ?>
					</div>
				</div>