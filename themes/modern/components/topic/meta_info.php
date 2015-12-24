				<div class="topic-info-box info-box <?php echo $topic->getSlug(); ?>">
					<div class="topic-picture" style="background-image: url('<?php echo $topic->getImage()->getUrl(); ?>');"></div>
					<div class="topic-info">
						<h1><?php echo $topic->getName(); ?></h1>
						<?php echo '<p>'.$topic->getText().'</p>'; ?>
					</div>
				</div>