	<!-- Phoenix -->
	<?php //require_once('frontpage/phoenix.php'); ?>
	<!-- End of Phoenix -->

	<div class="container_12">

		<!-- News banner -->
		<!-- <div class="grid_12 banner">
			<p>What did you think of the Summer Ball? Whether you went or not, we want to <a href="<?php echo STANDARD_URL; ?>summerball/">hear from you!</a></p>
		</div> -->
		<!-- End of news banner -->

		<!-- Sidebar -->
		<div class="sidebar grid_4 push_8">
			<?php
				include_once('sidebar/fbLikeBox.php');
				include_once('sidebar/mediaBox.php');
				include_once('sidebar/socialLinks.php');
				include_once('sidebar/fbActivity.php');
				include_once('sidebar/mostPopular.php');
				include_once('sidebar/iscience.php');
				include_once('sidebar/recentcomments.php');
			?>
		</div>
		<!-- End of sidebar -->

		<!--Featured container -->
			<?php include('frontpage/layout1.php'); ?>
		<!-- End of featuredcontainer -->
		<div class="clear"></div>
	</div>

	<!-- Featured bar -->
	<div class="container_12">
	<?php
		//$sql = "SELECT * FROM top_extrapage_cat WHERE loc='default' LIMIT 1";
		$sql = "SELECT id FROM `category` WHERE active=1 AND id>0 ORDER BY id ASC";
		$rsc = mysql_query($sql,$cid);
		if (!mysql_num_rows) die;
		$i = 1;
		while ($cat = mysql_fetch_array($rsc)) {
			$categories[$i] = $cat['id'];
			$i++;
		}
		//$categories = mysql_fetch_array($rsc);
		for ($i=1;$i<EXTRANEWS_COLS;$i++) {
			$category = $categories[$i];
			$cat = get_category_cat($category);
			list($a1,$a2,$a3,$a4) = get_category_topstories($category);
	?>
		<div class="grid_3 featuredBar <?php if ($i % 4 == 0 && $i != 0) echo 'last';?>">
			<div class="border <?php echo get_article_category_cat($a1);?>">
				<h3><a href="<?php echo get_article_category_cat($a1);?>/"><?php echo get_article_category($a1);?></a></h3>
				<a href="<?php echo article_url($a1);?>">
					<img id="featuredBarPhoto" alt="<?php echo get_img_title(get_img_id($a1,1));?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($a1, 1));?>&h=120px&w=220px&zc=1&a=t" width="220px" height="120px">
				</a>
				<h4><a href="<?php echo article_url($a1);?>"><?php echo get_article_title($a1);?></a></h4>
				<p><?php echo get_article_preview_trunc($a1,10);?></p>
			</div>
		</div>
		<?php } ?>
		<div class="clear"></div>
	</div>
	<!-- End of featured bar -->

	<!-- End ... -->
