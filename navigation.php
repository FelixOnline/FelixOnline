<!-- Navigation -->
<div class="navigation container_12">
	<div class="grid_12 clearfix">
		<ul class="navbar" class="clearfix" style="height: 27px;">
			<?php 
			if ($article != '') {
				$category = get_article_category_cat($article);
			} else if ($_GET['cat'] != '') {
				$category = $_GET['cat'];
			}
				
			$sql = "SELECT label,cat FROM `category` WHERE hidden=0 AND id>0 AND `order`>0 ORDER BY `order` ASC";
			$cats = mysql_query($sql,$cid);
			while ($cat = mysql_fetch_array($cats)) {
				$url = $cat['cat'];
				$titleNav = $cat['label'];
				$li = '<li ';
				$li .= 'class="'.$url.''.($category==$url ? ' selected': '').''.($url=='news' ? ' first': '').''.($url=='sport' ? ' last': '').'"';
				$li .= '><a href="'.$url.'/">' . $titleNav . '</a></li>';
				echo $li;
			}	
			?>
		</ul>
	</div>
</div>
<!-- End of Navigation -->
