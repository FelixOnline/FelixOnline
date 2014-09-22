<!-- In This Issue story -->
				<div class="panel felix-item-panel">
					<div class="row">
						<div class="small-6 columns felix-item-text">
							<span class="felix-item-cat felix-item-cat-<?php echo $article->getCategory()->getCat(); ?>"><?php echo $article->getCategory()->getCat(); ?></span>
							<h4 class="felix-item-title"><a href="<?php echo $article->getURL();?>"><?php echo $article->getTitle(); ?></a></h4>
						</div>
						<div class="small-6 columns felix-item-pic">
						<?php if ($image = $article->getImage()) { ?>
								<a href="<?php echo $article->getURL();?>">
									<img alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(850, 850);?>">
								</a>
						<?php } else { ?>
								<a href="<?php echo $article->getURL();?>">
									<img alt="" src="<?php echo IMAGE_URL.'850/850/'.DEFAULT_IMG_URI; ?>">
								</a>
						<?php } ?>
						</div>
					</div>
				</div>
<!-- End of In This Issue story -->
