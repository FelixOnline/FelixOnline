              <?php $comments = $article->getNumValidatedComments(); ?>
              <div class="article-block large"<?php if($equalizer): ?> data-equalizer-watch="<?php echo $equalizer; ?>"<?php endif; ?>>
                <a href="<?php echo $article->getUrl(); ?>">
                  <div class="article-img" style="background-image: url('<?php if($article->getImage()): echo $article->getImage()->getUrl(); else: echo \FelixOnline\Core\Settings::get('image_url').\FelixOnline\Core\Settings::get('default_img_uri'); endif; ?>');">
                    <?php if($show_category): ?><div class="article-category <?php echo $article->getCategory()->getCat(); ?> radius"><?php echo $article->getCategory()->getLabel(); ?></div><?php endif; ?>
                    <?php if($comments > 0): ?><div class="article-comments radius"><span class="glyphicons glyphicons-comments"></span>&nbsp;<?php echo $comments; ?></div><?php endif; ?>
                  </div>
                </a>
                <?php
                  if($headshot):
                ?>
                <div class="row editor-icons collapse">
                  <div class="small-1 large-1 columns">
                    <?php $author0 = $article->getAuthors()[0]; ?>
                    <img src="<?php echo $author0->getImage()->getUrl(200,200); ?>" alt="Headshot">
                  </div>
                  <div class="small-11 large-11 columns authors">
                    <?php echo strip_tags($article->getAuthorsEnglish()); ?>
                  </div>
                </div>
                <?php
                  endif;
                ?>
                <div class="article-title"><a href="<?php echo $article->getUrl(); ?>"><?php echo $article->getTitle(); ?></a></div>
                <div class="article-byline"><?php echo $article->getTeaser(); ?></div>
                <div class="article-time"><span class="glyphicons glyphicons-clock"></span><?php echo Utility::getRelativeTime($article->getPublished()); ?></div>
              </div>