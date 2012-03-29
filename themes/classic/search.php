<?php
$timing->log('frontpage');

$header = array(
    'title' => 'Felix Online - The student voice of Imperial College London',
    'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header); 
$timing->log('after header');

?>

	<div class="container_12">
	
		<?php
			/*TODO:
			
			*/
		?>
		
		<!-- Sidebar -->
		<div class="sidebar grid_4 push_8">
			<?php 
	            $theme->render('sidebar/fbActivity');
	            $theme->render('sidebar/mostPopular');
	            $theme->render('sidebar/socialLinks');

			?>
		</div>
		<!-- End of sidebar -->
		
		<!--Search container -->
		<div class="grid_8 pull_4">
			<?php 
				global $cid;
				$search = array(); // array to contain search results
				$people = array(); // array to contain people results
				
				if (!isset($_GET['p'])){ 
					$p=1;
				} else {
					$p = $_GET['p'];
				}
	
				$param = trim($_GET["q"]);
				$param = str_replace(" ", "%", $param);
				
				if(strlen($param) < 2 || $param == 'Search Felix Online...') { 
					$toofew = true;
				} else {
					// people seach
					$peopleparam = trim($_GET['q']);
					
					if(strlen($peopleparam) > 2) {
						$peopleparam = explode(" ", $peopleparam);
						// firstname search
						$sql = "SELECT user,name FROM user WHERE name LIKE '".$peopleparam[0]."%' ORDER BY name ASC";
						$peoplequery = mysql_query($sql);
						while (list($user, $name) = mysql_fetch_array($peoplequery)) { 
							array_push($people, $name.'+'.$user);
						}
						
						if($peopleparam[1]){
							// second name search
							$sql = "SELECT user,name FROM user WHERE name LIKE '%".$peopleparam[1]."' ORDER BY name ASC";
							$peoplequery = mysql_query($sql);
							while (list($user, $name) = mysql_fetch_array($peoplequery)) { 
								array_push($people, $name.'+'.$user);
							}
						} else {
							// second name search
							$sql = "SELECT user,name FROM user WHERE name LIKE '%".$peopleparam[0]."' ORDER BY name ASC";
							$peoplequery = mysql_query($sql);
							while (list($user, $name) = mysql_fetch_array($peoplequery)) { 
								array_push($people, $name.'+'.$user);
							}
						}
					}
					
					$people = array_unique($people);
					$peoplerows = count($people);
					
					// title search
					$sql = "SELECT id FROM article WHERE title LIKE '%$param%' AND hidden = 0 AND published < NOW() ORDER BY date DESC";
					$rows = mysql_num_rows(mysql_query($sql,$cid));
					
					$query = mysql_query($sql);
					while (list($article) = mysql_fetch_array($query)) { 
						array_push($search, $article);
					}
					
					// if number of results from title is less than 4 then add content search as well
					$sql = "SELECT article.id FROM `article` INNER JOIN `text_story` ON (article.text1=text_story.id) WHERE text_story.content LIKE '%$param%' AND article.hidden = 0 AND article.published < NOW() ORDER BY article.date DESC";
					$query = mysql_query($sql);
					while (list($article) = mysql_fetch_array($query)) { 
						array_push($search, $article);
					}
					
					$rows = count($search);
				}
			?>
			<?php if($toofew) { ?>
				<p>Uh oh! You did not specify enough search terms. Please try again!</p>
			<?php } else { ?>
			<h2>Search results for "<?php echo $_GET["q"];?>" -  <?php echo $rows;?> results</h2>
			<?php 
				if ($rows == 0 && $peoplerows == 0) { ?>
				
					<p>Uh oh! We couldn't find what you were looking for. Please try again!</p>
			
			<?php  
				} else {
			?>
			
			<?php 
			
				if($peoplerows) { ?>
					<div id="peopleresult">
						<h3>People</h3>
						<ul>
				<?php
					foreach($people as $person) { 
						$details = explode('+', $person);
					?>
					
					<li><a href="user/<?php echo $details[1];?>/"><?php echo $details[0];?></a></li>
					
				<?php
					} ?>
					</ul>
				</div>
			<?php	}
			?>
			
			<?php if($rows) { ?>
			
			<div id="articleListCont">
				<h3>Articles</h3>
			
			<?php 

			//while (list($article) = mysql_fetch_array($query)) { 
			$start = ($p-1)*ARTICLES_PER_CAT_PAGE;
			$end = ARTICLES_PER_CAT_PAGE + $start;
			if($end > $rows)
				$end = $rows;
			
			for($a=$start; $a<$end; $a++) {
			//foreach($search as $article) {
				$article = $search[$a];
			?>

				<?php 
				if($p == 1) {
					$i++;
					if ($i < 4) {
				?>
				<div class="userArticle">
					<div class="userArticleDate grid_1 alpha">
						<span><?php echo date('jS',get_article_date($article)); ?></span><br/>
						<?php echo date('F Y',get_article_date($article)); ?><br/>
					</div>
					<div class="userArticleInfo grid_7 omega <?php if (get_article_category_cat($article) == 'comment') echo 'second';?>">
						<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
						<div class="subHeader">
							<p><?php echo get_article_preview_trunc($article, 30); ?></p>
							<div id="storyMeta">
								<ul class="metaList">
									<li id="category"><a href="<?php echo get_article_category_cat($article);?>" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
									<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php $num_comments = get_article_comments($article); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
								</ul>
							</div>
						</div>
						<?php if (get_article_category_cat($article) != 'comment') { ?>
							<div id="secondStoryPic">
								<a href="<?php echo article_url($article);?>">
									<img id="secondStoryPhoto" alt="<?php echo $image_title;?>" src="../inc/timthumb.php?src=../<?php echo get_img_uri(get_img_id($article, 1));?>&h=130px&w=220px&zc=1&a=t">
								</a>
							</div>
						<?php } ?>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<?php } else { ?>
				<div class="userArticle">
					<div class="userArticleDate grid_1 alpha">
						<span><?php echo date('jS',get_article_date($article)); ?></span><br/>
						<?php echo date('F Y',get_article_date($article)); ?>
						<div><?php //echo get_article_hits($article); ?> hits</div>
					</div>
					<div class="userArticleInfo grid_7 omega second">
						<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
						<div class="subHeader">
							<p><?php echo get_article_preview_trunc($article, 30); ?></p>
							<div id="storyMeta">
								<ul class="metaList">
									<li id="category"><a href="<?php echo get_article_category_cat($article);?>" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
									<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php $num_comments = get_article_comments($article); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
								</ul>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>	
			<?php } 
				} else { ?>
				<div class="userArticle">
					<div class="userArticleDate grid_1 alpha">
						<span><?php echo date('jS',get_article_date($article)); ?></span><br/>
						<?php echo date('F Y',get_article_date($article)); ?>
						<div><?php //echo get_article_hits($article); ?> hits</div>
					</div>
					<div class="userArticleInfo grid_7 omega second">
						<h3><a href="<?php echo article_url($article);?>"><?php echo get_article_title($article);?></a></h3>
						<div class="subHeader">
							<p><?php echo get_article_preview_trunc($article, 30); ?></p>
							<div id="storyMeta">
								<ul class="metaList">
									<li id="category"><a href="<?php echo get_article_category_cat($article);?>" class="<?php echo get_article_category_cat($article);?>"><?php echo get_article_category($article);?></a></li>
									<li id="comments"><a href="<?php echo article_url($article);?>#commentHeader"><?php $num_comments = get_article_comments($article); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
								</ul>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>	
			
			<?php }
				}
			?>
			</div>
			
			<!-- Page list -->
			<div class="featBox>">
				<ul id="pageList">
					<li id="desc">Pages:</li>
					<?php if ($p != 1) // Previous page arrow
							echo '<li class="arrow"><a href="search/?q='.$_GET["q"].'&p='.($p-1).'">&#171;</a></li>';
									
						$pages = ceil(($rows-ARTICLES_PER_USER_PAGE)/ARTICLES_PER_USER_PAGE)+1;
						if ($pages>1) {
							$span = NUMBER_OF_PAGES_IN_PAGE_LIST;
							if ($pages > $span) {
								if ($p >= ($span/2)) {
									$start = ($p - $span/2)+1;
									$limit = $p + $span/2;
									if ($limit > $pages) {
										$limit = $pages;
										$start = $limit - $span;
									}
								} else {
									$start = 1;
									$limit = $span;
								}
							} else {
								$limit = $pages;
								$start = 1;
							}
							for ($i=$start;$i<=$limit;$i++)
								echo (($p==$i)?'<li class="selected">':('<li><a href="search/?q='.$_GET["q"].'&p='.$i.'">')).$i.(($p==$i)?'</li>':'</a></li>');
						} else {
							echo '<li class="selected">1</li>';
						}
						if ($p != $pages) // Next page arrow
							echo '<li class="arrow"><a href="search/?q='.$_GET["q"].'&p='.($p+1).'">&#187;</a></li>';
					?>
				</ul>
			</div>
			<div class="clear"></div>
			<?php } 
				}
			?>
		</div>
		<?php } ?>
		<!-- End of search container -->
		<div class="clear"></div>
	</div>
	
<?php $timing->log('end of search');?>
<?php $theme->render('footer'); ?>