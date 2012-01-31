<?php
    // Initialise featured articles
    $sql = "SELECT
            top_sidebar_1,
            top_sidebar_2,
            top_sidebar_3,
            top_sidebar_4,
            top_sidebar_5 
            FROM `category` 
            WHERE id='".$article->getCategoryCat()."'";
    $featured = $db->get_row($sql);
?>
<div id="featuredBox">
	<h3>Top <span class="<?php echo $article->getCategoryCat();?>"><?php echo $article->getCategoryLabel();?></span> Stories</h3>
	<ul class="clearfix">
    <?php 
        foreach($featured as $key => $value) { 
            $article = new Article($value);
            if($key == 'top_sidebar_1') {
        ?>
                <li class="withPic">
                    <a href="<?php echo $article->getURL(); ?>">
                        <h5><?php echo $article->getTitle();?></h5>
                        <div class="featuredPic">
                            <a href="<?php echo $article->getURL(); ?>">
                                <img id="featuredPhoto" alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(150, 100); ?>">
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
