<?php if(count($topics) > 0): ?>
			<div class="article-topics info-box">
				<h1>More on</h1>
				<?php foreach($topics as $topic): ?>
					<?php $theme->render('components/article/topic_block', array('topic' => $topic));
				<?php endforeach; ?>
			</div>
<?php endif; ?>