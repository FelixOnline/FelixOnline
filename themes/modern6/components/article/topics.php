<?php if(count($topics) > 0): ?>
		<div class="article-topics">
			<p><b>More on this</b>
			<?php
				foreach($topics as $topic):
					$theme->render('components/article/topic_block', array('topic' => $topic));
				endforeach;
			?>
		</div>
<?php endif; ?>