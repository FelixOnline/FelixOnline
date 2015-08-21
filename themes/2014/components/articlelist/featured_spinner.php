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
									<img alt="" src="<?php echo \FelixOnline\Core\Settings::get('image_url').'650/300/'.\FelixOnline\Core\Settings::get('default_img_uri'); ?>">
								</a>
						<?php } ?>
						</div>
						<div class="felix-featured-subcaption"><?php echo $article->getTeaser();?></div>
						</div>
					</div>
<!-- End of spinner story -->
