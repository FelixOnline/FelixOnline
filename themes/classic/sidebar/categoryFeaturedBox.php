<?php
    // Initialise featured articles
    $featured = $category->getTopStories();
?>
<div id="featuredBox">
    <h3>Top Stories</h3>
    <ul class="clearfix">
    <?php
        foreach($featured as $key => $article) {
            if($key == 'top_story_1') {
        ?>
                <li class="withPic">
                    <a href="<?php echo $article->getURL(); ?>">
                        <h5><?php echo $article->getTitle();?></h5>
                        <div class="featuredPic">
                            <a href="<?php echo $article->getURL(); ?>">
			                    <?php if ($article->getImage()): ?>
                                	<img id="featuredPhoto" alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(150, 100); ?>">
								<?php else: ?>
			                    	<img id="featuredPhoto" alt="" src="<?php echo IMAGE_URL.'/150/100/'.DEFAULT_IMG_URI; ?>">
			                    <?php endif; ?>
			                </a>
                        </div>
                    </a>
                </li>
        <?php } else { ?>
                <li>
                    <a href="<?php echo $article->getURL(); ?>">
                        <?php echo $article->getTitle();?>
                    </a>
                </li>
        <?php } 
        } ?>
    </ul>
</div>
