<!-- Navigation -->
<div class="navigation container_12">
	<div class="grid_12 clearfix">
		<ul id="navbar" class="clearfix">
			<?php 
			if ($theme->isPage('article')) {
                $check = $article->getCategoryCat();
			} else if ($theme->isPage('category')) {
				$check = $category->getCat();
			}
				
            $sql = "SELECT 
                        label,
                        cat 
                    FROM `category` 
                    WHERE hidden=0 
                    AND id>0 
                    ORDER BY id ASC";
            $cats = $db->get_results($sql);
            foreach($cats as $key => $cat) { ?>
                <li class="<?php echo $cat->cat; ?> <?php if($check==$cat->cat) echo 'selected'; ?> <?php if($cat->cat == 'news') echo 'first'; ?> <?php if($cat->cat == 'sport') echo 'last'; ?>">
                    <a href="<?php echo STANDARD_URL.$cat->cat; ?>/">
                        <?php echo $cat->label; ?>
                    </a>
                </li>
            <?php } ?>	
		</ul>
	</div>
</div>
<!-- End of Navigation -->
