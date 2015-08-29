			<?php
				/* First page */
				if($pagenum == 1) { 
					if (count($articles) == 0) {
						?>There are no articles in this category.<?php
					} else {
						foreach($articles as $key => $article) {
							if($key == 0) { // top story 
								$theme->render('components/articlelist/article_large', array(
									'article' => $article,
									'show_authors' => true
								));
							} else { // middle stories 
								$theme->render('components/articlelist/article_medium', array(
									'article' => $article,
									'show_authors' => true
								));
							}
						}
					}
				} else {
					/* Not first page */
					if (count($articles) == 0) {
						 ?> Could not find any articles.<?php
					} else {
						foreach($articles as $article) {
							$theme->render('components/articlelist/article_medium', array(
								'article' => $article,
								'show_authors' => true
							));
						}
					}
				}
			?>