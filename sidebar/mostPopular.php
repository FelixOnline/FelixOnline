<div id="mostPopular">
	<h3>Most Popular</h3>
	<ul class="popularNav">
		<li class="selected"><a href="#mostPopRead">Read</a></li>
		<li><a href="#mostPopComment">Commented</a></li>
	</ul>
	<div class="mostPopularTab" id="mostPopRead">
		<ol>
		<?php 
			$viewed_articles = get_mostviewed_articles();
			foreach ($viewed_articles as $each_article)
				echo '<li><a href="'.article_url($each_article).'">'.get_article_title($each_article).'</a></li>';
		?>
		</ol>
	</div>
	<div class="mostPopularTab" id="mostPopComment">
		<ol>
		<?php
			$popular_articles = get_popular_articles();
			foreach ($popular_articles as $peach_article) {
				echo '<li><a href="'.article_url($peach_article).'">'.get_article_title($peach_article).'</a></li>';
			}
		?>
		</ol>
	</div>
</div>