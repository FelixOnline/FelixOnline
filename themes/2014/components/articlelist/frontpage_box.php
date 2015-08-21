<!-- In This Issue story -->
				<?php $article = $article->getArticle(); ?>
				<div class="medium-4 columns">
				<div class="panel felix-item-panel">
					<div class="row">
						<div class="small-6 columns felix-item-text">
							<span class="felix-item-cat felix-item-cat-<?php echo $article->getCategory()->getCat(); ?>"><?php echo $article->getCategory()->getLabel(); ?></span>
							<h4 class="felix-item-title"><a href="<?php echo $article->getURL();?>"><?php echo $article->getTitle(); ?></a></h4>
						</div>
						<div class="small-6 columns felix-item-pic">
						<?php if ($image = $article->getImage()) { ?>
								<a href="<?php echo $article->getURL();?>">
									<img alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(850, 850);?>">
								</a>
						<?php } else { ?>
								<a href="<?php echo $article->getURL();?>">
									<img alt="" src="<?php echo \FelixOnline\Core\Settings::get('image_url').'850/850/'.\FelixOnline\Core\Settings::get('default_image_uri'); ?>">
								</a>
						<?php } ?>
						</div>
					</div>
				</div>
				</div>
<!-- End of In This Issue story -->
