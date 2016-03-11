<?php $comments = $article->getNumValidatedComments(); ?>
<div class="article-block section <?php echo $article->getCategory()->getCat(); ?>">
	<div class="carousel" data-equalizer-watch="carousel">
		<div class="row">
			<div class="medium-12 columns">
				<a href="<?php echo $article->getUrl(); ?>">
					<div class="article-img">
						<div class="article-img-inner" style="background-image: url('<?php if($article->getImage()): echo $article->getImage()->getUrl(); else: echo \FelixOnline\Core\Settings::get('image_url').\FelixOnline\Core\Settings::get('default_img_uri'); endif; ?>');">
							<div class="article-category <?php echo $article->getCategory()->getCat(); ?> radius"><?php if($article->getVideoUrl()): ?><span class="glyphicons glyphicons-facetime-video"></span><?php endif; ?><?php echo $article->getCategory()->getLabel(); ?></div>
							<?php if($comments > 0): ?><div class="article-comments radius"><span class="glyphicons glyphicons-comments"></span>&nbsp;<?php echo $comments; ?></div><?php endif; ?>
						</div>
					</div>
				</a>
			</div>
			<div class="medium-12 columns">
				<div class="article-title"><a href="<?php echo $article->getUrl(); ?>"><?php echo $article->getTitle(); ?></a></div>
				<div class="article-byline"><?php echo $article->getTeaser(); ?></div>
			</div>
		</div>
	</div>
</div>