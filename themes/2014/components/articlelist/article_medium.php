<!-- Top story -->
				<div class="felix-front-news-main felix-news-dotted">
					<div class="row">
						<div class="medium-4 columns felix-front-news-main-img">
						<?php if ($image = $article->getImage()) { ?>
								<a href="<?php echo $article->getURL();?>">
								<?php if($image->isTall(800, 800)) { ?>
									<img alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(500, 600);?>">
								<?php } else { ?>
									<img alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(800, 600);?>">
								<?php } ?>
								</a>
						<?php } else { ?>
								<a href="<?php echo $article->getURL();?>">
									<img alt="" src="<?php echo \FelixOnline\Core\Settings::get('image_url').'800/600/'.\FelixOnline\Core\Settings::get('default_image_uri'); ?>">
								</a>
						<?php } ?>
						</div>
						<div class="medium-8 columns felix-front-news-main-text">
							<span class="felix-front-news-date"><?php echo date("l F j, Y",$article->getPublished());?><?php if($show_authors): echo " • ".Utility::outputUserList($article->getAuthors()); endif; ?></span>
							<h3><a href="<?php echo $article->getURL();?>"><?php echo $article->getTitle();?></a></h3>
							<p><?php echo $article->getTeaser();?></p>
							<div class="article-comments"><span class="comment-count"><a href="<?php echo $article->getURL().'#commentHeader';?>"><?php echo $article->getNumComments().'</a></span> comment'.($article->getNumComments() != 1 ? 's' : '');?>.<?php if ($article->canComment($currentuser)) { ?> <a href="<?php echo $article->getURL().'#commentForm';?>">Post your own now</a>!<?php } ?></div>
						</div>
					</div>
				</div>
<!-- End of top story -->
