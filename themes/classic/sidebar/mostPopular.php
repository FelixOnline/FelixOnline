<?php
    $cachemp = new Cache('mostPopular');
    if($cachemp->start()) {
?>
<div id="mostPopular">
	<h3>Most Popular</h3>
	<ul class="popularNav">
		<li class="selected"><a href="#mostPopRead">Read</a></li>
		<li><a href="#mostPopComment">Commented</a></li>
	</ul>
	<div class="mostPopularTab" id="mostPopRead">
		<ol>
            <?php 
                $sql = "SELECT DISTINCT article AS id,COUNT(article) AS c 
                FROM (
                    SELECT article FROM article_visit AS av 
                    INNER JOIN article AS a 
                    ON (av.article=a.id) 
                    WHERE a.published IS NOT NULL 
                    ORDER BY timestamp DESC LIMIT 500
                ) AS t GROUP BY article ORDER BY c DESC LIMIT 5";
                $viewed_articles = $db->get_results($sql);
				if(is_array($viewed_articles)) {
	                foreach($viewed_articles as $object) {
	                    $article = new Article($object->id);
                ?>
                    <li>
                        <a href="<?php echo $article->getURL(); ?>">
                            <?php echo $article->getTitle(); ?>
                        </a>
                    </li>
                <?php }
                } ?>
		</ol>
	</div>
	<div class="mostPopularTab" id="mostPopComment">
		<ol>
            <?php
                $sql = "SELECT article AS id,SUM(count) AS count 
                FROM (
                        (SELECT c.article,COUNT(*) AS count 
                        FROM `comment` AS c 
                        INNER JOIN `article` AS a ON (c.article=a.id) 
                        WHERE c.`active`=1 
                        AND timestamp>(DATE_SUB(NOW(),INTERVAL ".MOST_POPULAR_INTERVAL." day)) 
                        AND a.published<NOW() 
                        GROUP BY article 
                        ORDER BY timestamp DESC 
                        LIMIT 20) 
                    UNION ALL 
                        (SELECT ce.article,COUNT(*) AS count 
                        FROM `comment_ext` AS ce 
                        INNER JOIN `article` AS a ON (ce.article=a.id) 
                        WHERE ce.`active`=1 
                        AND pending=0 
                        AND timestamp>(DATE_SUB(NOW(),INTERVAL ".MOST_POPULAR_INTERVAL." day)) 
                        AND a.published<NOW() 
                        GROUP BY article 
                        ORDER BY timestamp DESC)
                ) AS t 
                GROUP BY article 
                ORDER BY count DESC, article DESC LIMIT ".POPULAR_ARTICLES; // go for most recent comments instead
                $popular_articles = $db->get_results($sql);
  				if(is_array($popular_articles)) {
	                foreach ($popular_articles as $object) {
	                    $article = new Article($object->id);
                ?>
                    <li>
                        <a href="<?php echo $article->getURL(); ?>">
                            <?php echo $article->getTitle(); ?>
                        </a>
                    </li>
            <?php }
            }?>
		</ol>
	</div>
</div>
<?php
    } $cachemp->stop();
?>
