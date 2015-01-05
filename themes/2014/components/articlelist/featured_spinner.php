<!-- Spinner story -->
					<?php $article = $article->getArticle(); ?>
					<div>
						<div class="felix-featured-caption"><a href="<?php echo $article->getURL();?>"><?php echo $article->getTitle(); ?></a></div>
						<div class="felix-featured-image-container">
						<div class="felix-featured-image">
						<?php if ($image = $article->getImage()) { ?>
								<a href="<?php echo $article->getURL();?>">
									<img alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(1200);?>">
								</a>
						<?php } else { ?>
								<a href="<?php echo $article->getURL();?>">
									<img alt="" src="<?php echo IMAGE_URL.'650/300/'.DEFAULT_IMG_URI; ?>">
								</a>
						<?php } ?>
						</div>
						<div class="felix-featured-subcaption"><?php echo $article->getTeaser();?></div>
						</div>
					</div>
<!-- End of spinner story -->
