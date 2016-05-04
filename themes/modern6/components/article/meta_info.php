<div class="article-author-box <?php echo $article->getCategory()->getCat(); ?>">
	<div class="authors show-for-medium">
		<?php foreach($article->getAuthors() as $author) {
			$theme->render('components/article/meta_author', array('user' => $author));
		} ?>
	</div>
	<div class="authors show-for-small-only">
		<?php
			$text = array();
			foreach($article->getAuthors() as $author) {
				$text[] = '<a href="'.$author->getURL().'">'.$author->getName().'</a>';
			}

			if(count($text) > 1) {
				$last = array_pop($text);
				array_push($text, 'and '.$last);
			}
			
			echo 'By '.implode(', ', $text);
		?>
	</div>
	<p class="date">
		<?php echo $article->getPublished() ? date('l M j, Y', $article->getPublished()) : "<strong>Not Published</strong>";?>	<b class="category-label <?php echo $article->getCategory()->getCat(); ?>">
			<a href="<?php echo $article->getCategory()->getURL(); ?>"><?php echo $article->getCategory()->getLabel(); ?></a>
		</b>
	</p>
	<?php $numC = $article->getNumValidatedComments(); ?>
	<p class="comments show-for-medium">
		There <?php echo ($numC != 1 ? 'are' : 'is');?> <a href="<?php echo Utility::currentPageURL().'#commentHeader';?>">
			<b><?php echo $numC ?></b>
		</a> comment<?php echo ($numC != 1 ? 's' : '');?>. 
		<?php if ($article->getCommentStatus()->getId() != \FelixOnline\Core\ArticleCommentStatus::ARTICLE_COMMENTS_OFF) { ?>
			<a href="<?php echo Utility::currentPageURL().'#commentForm';?>">Post your own</a>
		<?php } ?>
	</p>
</div>