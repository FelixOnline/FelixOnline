                <?php $comments = $article->getNumValidatedComments(); ?>
                <div class="article-block section <?php echo $article->getCategory()->getCat(); ?>">
                  <div class="date"<?php if($equalizer): ?> data-equalizer-watch="<?php echo $equalizer; ?>"<?php endif; ?>>
                    <div class="row" data-equalizer-watch="<?php echo $equalizer; ?>" data-equalizer="date-article-<?php echo $article->getId(); ?>" data-equalizer-mq="medium-only">
                      <div class="small-12 medium-6 large-12 columns">
                        <a href="<?php echo $article->getUrl(); ?>">
                          <div class="article-img">
                            <div class="article-img-inner" style="background-image: url('<?php if($article->getImage()): echo $article->getImage()->getUrl(); else: echo \FelixOnline\Core\Settings::get('image_url').\FelixOnline\Core\Settings::get('default_img_uri'); endif; ?>');">
                            	<?php if(!$show_category && $article->getVideoUrl()): ?><div class="article-video radius"><span class="glyphicons glyphicons-facetime-video"></span></div><?php endif; ?>
                              <?php if($show_category): ?><div class="article-category <?php echo $article->getCategory()->getCat(); ?> radius"><?php if($article->getVideoUrl()): ?><span class="glyphicons glyphicons-facetime-video"></span><?php endif; ?><?php echo $article->getCategory()->getLabel(); ?></div><?php endif; ?>
                            	<?php if($comments > 0): ?><div class="article-comments radius"><span class="glyphicons glyphicons-comments"></span>&nbsp;<?php echo $comments; ?></div><?php endif; ?>
                          	</div>
                          </div>
                        </a>
                      </div>
                      <div class="small-12 medium-6 large-12 columns" data-equalizer-watch="date-article-<?php echo $article->getId(); ?>">
                        <?php
                        	if($headshot):
                        ?>
                    		<div class="row editor-icons collapse">
                    			<div class="small-1 large-2 columns">
                    				<?php
                              $author0 = $article->getAuthors()[0];
                              if($author0 && $author0->getImage()):
                            ?>
                    				<img src="<?php echo $author0->getImage()->getUrl(200,200); ?>" alt="Headshot">
                            <?php
                              endif;
                            ?>
                    			</div>
                    			<div class="small-11 large-10 columns authors">
                    				<?php echo strip_tags($article->getAuthorsEnglish()); ?>
                    			</div>
                    		</div>
                    	  <?php
                    	    endif;
                    	  ?>
                        <div class="article-title"><?php if ($article->getIsLive()): ?><span class="live-label">LIVE</span><?php endif; ?><a href="<?php echo $article->getUrl(); ?>"><?php echo $article->getTitle(); ?></a></div>
                        <div class="article-byline"><?php echo $article->getTeaser(); ?></div>
                        <div class="article-time"><span class="glyphicons glyphicons-clock"></span><?php echo Utility::getRelativeTime($article->getPublished()); ?></div>
                      </div>
                    </div>
                  </div>
                </div>