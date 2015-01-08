				<div class="article-meta">
					<div class="article-authors">
						<div class="container">
						<?php foreach($article->getAuthors() as $author) {
							$theme->render('components/articleAuthor', array('user' => $author));
						} ?>
						</div>
					</div>
					<div class="article-date">Published on <?php echo $article->getPublished() ? date('l F j, Y \a\t H:i', $article->getPublished()) : "<strong>Not Published</strong>";?>.</div>
					<div class="article-comments"><span class="comment-count"><a href="<?php echo Utility::currentPageURL().'#commentHeader';?>"><?php echo $article->getNumComments().'</a></span> comment'.($article->getNumComments() != 1 ? 's' : '');?>.<?php if ($article->canComment($currentuser)) { ?> <a href="<?php echo Utility::currentPageURL().'#commentForm';?>">Post your own now</a>!<?php } ?></div>
					<?php
						$isSectionEditor = false;
						if($article->getCategory()->getEditors() != null) {
							foreach($article->getCategory()->getEditors() as $user) {
								if($currentuser->getUser() == $user->getUser()) {
									$isSectionEditor = true;
								}
							}
						}
					?>
				</div>