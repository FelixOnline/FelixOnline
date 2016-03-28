<?php $comments = $article->getNumValidatedComments(); ?>
<div class="article-block section <?php echo $article->getCategory()->getCat(); ?>">
	<div class="large"<?php if($equalizer): ?> data-equalizer-watch="<?php echo $equalizer; ?>"<?php endif; ?>>
		<a href="<?php echo $article->getUrl(); ?>">
			<div class="article-img">
				<div class="article-img-inner" style="background-image: url('<?php if($article->getImage()): echo $article->getImage()->getUrl(); else: echo \FelixOnline\Core\Settings::get('image_url').\FelixOnline\Core\Settings::get('default_img_uri'); endif; ?>');">
					<?php if($comments > 0): ?><div class="article-comments radius"><span class="glyphicons glyphicons-comments"></span>&nbsp;<?php echo $comments; ?></div><?php endif; ?>
				</div>
			</div>
		</a>
		<?php
			if($headshot):
		?>
		<div class="row box-editor-icons">
			<div class="small-12 columns">
				<?php
					$author0 = $article->getAuthors()[0];
					if($author0 && $author0->getImage()):
				?>
				<img src="<?php echo $author0->getImage()->getUrl(200,200); ?>" alt="Headshot">
				<?php
					endif;
				?>
				<span class="authors">
					<?php echo strip_tags($article->getAuthorsEnglish()); ?>
				</span>
			</div>
		</div>
		<?php
			endif;
		?>
			<div class="article-title">
				<a href="<?php echo $article->getUrl(); ?>">
					<?php if ($article->getIsLive()): ?><span class="live-label">LIVE</span><?php endif; ?>
					<?php echo $article->getTitle(); ?></a>
				</div>
			<div class="article-byline">
				<?php if($article->getVideoUrl()): ?><span class="glyphicons glyphicons-facetime-video"></span> <b>WATCH:</b> <?php endif; ?>
				<?php echo $article->getTeaser(); ?>
			</div>
			<div class="article-time">
				<span class="glyphicons glyphicons-clock"></span><?php echo Utility::getRelativeTime($article->getPublished()); ?>
				<?php if($show_category): ?>
					<b class="category-label <?php echo $article->getCategory()->getCat(); ?>"><?php echo $article->getCategory()->getLabel(); ?></b>
				<?php endif; ?>
			</div>
	</div>
</div>