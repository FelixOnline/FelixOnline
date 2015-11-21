              <?php $comments = $article->getNumValidatedComments(); ?>
              <div class="article-block section <?php echo $article->getCategory()->getCat(); ?>">
                <div class="small"<?php if($equalizer): ?> data-equalizer-watch="<?php echo $equalizer; ?>"<?php endif; ?>>
                  <div class="article-title"><a href="<?php echo $article->getUrl(); ?>"><?php echo $article->getTitle(); ?></a></div>
                  <span class="article-time"><span class="glyphicons glyphicons-clock"></span><?php echo Utility::getRelativeTime($article->getPublished()); ?></span>
                  <?php if($comments > 0): ?><span class="article-inline-comments"><span class="glyphicons glyphicons-comments"></span>&nbsp;<?php echo $comments; ?></span><?php endif; ?>
                </div>
              </div>