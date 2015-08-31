			<div class="felix-pad-top"><?php $theme->render('components/advert', array('sidebar' => false)); ?></div>
			<!-- Articles -->
				<?php foreach($articles as $key => $article) {
					$theme->render('components/articlelist/article_medium', array(
						'article' => $article,
						'show_authors' => true
					));
				} ?> 
			<!-- End of articles -->