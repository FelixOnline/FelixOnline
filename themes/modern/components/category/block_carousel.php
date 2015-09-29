              <?php $comments = $article->getNumValidatedComments(); ?>
              <div class="article-block carousel" data-equalizer-watch="carousel">
                <div class="row">
                  <div class="medium-12 columns">
                    <a href="<?php echo $article->getUrl(); ?>">
                      <div class="article-img" style="background-image: url('<?php if($article->getImage()): echo $article->getImage()->getUrl(); else: echo \FelixOnline\Core\Settings::get('image_url').\FelixOnline\Core\Settings::get('default_img_uri'); endif; ?>');">
                        <div class="article-category <?php echo $article->getCategory()->getCat(); ?> radius"><?php echo $article->getCategory()->getLabel(); ?></div>
                        <?php if($comments > 0): ?><div class="article-comments radius"><span class="glyphicons glyphicons-comments"></span>&nbsp;<?php echo $comments; ?></div><?php endif; ?>
                      </div>
                    </a>
                  </div>
                  <div class="medium-12 columns">
                    <div class="article-title"><a href="<?php echo $article->getUrl(); ?>"><?php echo $article->getTitle(); ?></a></div>
                    <div class="article-byline"><?php echo $article->getTeaser(); ?></div>
                  </div>
                </div>
              </div>