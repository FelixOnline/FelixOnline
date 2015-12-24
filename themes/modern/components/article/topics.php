<?php if(count($topics) > 0): ?>
			<div class="article-topics info-box">
				<h1>More on</h1>
				<?php foreach($topics as $topic): ?>
					<a href="<?php echo $topic->getUrl(); ?>">
					<div class="article-topic" style="background-image: url('<?php echo $topic->getImage()->getUrl(); ?>');">
						<div class="article-topic-info">
							<h2><?php echo $topic->getName(); ?></h2>
							<span class="topic-start topic-date"><?php echo date('F Y', $topic->getStartDate()); ?></span> <span class="glyphicons glyphicons-chevron-right"></span> <span class="topic-start topic-date"><?php echo date('F Y', $topic->getEndDate()); ?></span>
						</div>
					</div>
					</a>
				<?php endforeach; ?>
			</div>
<?php endif; ?>