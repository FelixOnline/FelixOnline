						<div class="felix-pad-top"><?php $theme->render('components/advert', array('sidebar' => false)); ?></div>
						<?php foreach ($articles as $key => $article) { ?>
							<?php
							$theme->render('components/articlelist/article_medium', array(
								'article' => $article,
								'show_authors' => true
							));
							?>
						<?php } ?>