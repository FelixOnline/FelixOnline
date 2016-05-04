		<div class="article-info-box info-box <?php echo $article->getCategory()->getCat(); ?>">
			<div class="row full-width collapse">
				<div class="small-12 medium-6 large-12 columns">
					<?php foreach($article->getAuthors() as $author) {
						$theme->render('components/article/meta_author', array('user' => $author));
					} ?>
				</div>
				<div class="small-12 medium-6 large-12 columns">
					<p class="date">
						<span class="glyphicons glyphicons-clock"></span> <?php echo $article->getPublished() ? date('l M j, Y \a\t H:i', $article->getPublished()) : "<strong>Not Published</strong>";?>
					</p>
					<?php $numC = $article->getNumValidatedComments(); ?>
					<p class="comments">
						<span class="glyphicons glyphicons-comments"></span> 
						<a href="<?php echo Utility::currentPageURL().'#commentHeader';?>">
							<b><?php echo $numC ?></b>
						</a> comment<?php echo ($numC != 1 ? 's' : '');?>. 
						<?php if ($article->getCommentStatus()->getId() != \FelixOnline\Core\ArticleCommentStatus::ARTICLE_COMMENTS_OFF) { ?>
							<a href="<?php echo Utility::currentPageURL().'#commentForm';?>">Post your own</a>
						<?php } ?>
					</p>
				</div>
			</div>
		</div>