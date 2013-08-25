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
						$peoplequery = $db->get_results($sql);
						
						if (!is_null($peoplequery)) {
							foreach($peoplequery as $person) {
								array_push($people, $person->name.'+'.$person->user);
							}
						}
						
						if($peopleparam[1]){
							// second name search
							$sql = "SELECT user,name FROM user WHERE name LIKE '%".$peopleparam[0].' '.$peopleparam[1]."' ORDER BY name ASC";
							$peoplequery = $db->get_results($sql);
							
							if (!is_null($peoplequery)) {
								$people = array();
								foreach($peoplequery as $person) {
									array_push($people, $person->name.'+'.$person->user);
								}
							}
						} else {
							// second name search
							$sql = "SELECT user,name FROM user WHERE name LIKE '%".$peopleparam[0]."' ORDER BY name ASC";
							$peoplequery = $db->get_results($sql);
							
							if (!is_null($peoplequery)) {
								foreach($peoplequery as $person) {
									array_push($people, $person->name.'+'.$person->user);
								}
							}
						}
					}
					
					$people = array_unique($people);
					$peoplerows = count($people);
					
					// title search
					$sql = "SELECT id FROM article WHERE title LIKE '%$param%' AND hidden = 0 AND published < NOW() ORDER BY date DESC";
					$results = $db->get_results($sql);
					
					if (is_null($results)) {
						$rows = 0;
					} else {
						$rows = count($results);

						foreach ($results as $article) {
							$article = new Article($article->id);
							array_push($search, $article);
						}
					}
					
					// if number of results from title is less than 4 then add content search as well
					if ($rows < 4) {
						$sql = "SELECT article.id FROM `article` INNER JOIN `text_story` ON (article.text1=text_story.id) WHERE text_story.content LIKE '%$param%' AND article.hidden = 0 AND article.published < NOW() ORDER BY article.date DESC";
						$results = $db->get_results($sql);
						
						if (!is_null($results)) {
							foreach ($results as $article) {
								$article = new Article($article->id);
								array_push($search, $article);
							}
						}
						
						$rows = count($search);
					}
				}
			?>
			<?php if($toofew) { ?>
				<p>Uh oh! You did not specify enough search terms. Please try again!</p>
			<?php } else { ?>
			<h2>Search results for "<?php echo $_GET["q"];?>" -  <?php echo $rows + $peoplerows;?> results</h2>
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
						<span><?php echo date('jS',$article->getDate()); ?></span><br/>
						<?php echo date('F Y',$article->getDate()); ?><br/>
					</div>
					<div class="userArticleInfo grid_7 omega <?php if ($article->getCategory()->getCat() == 'comment') echo 'second';?>">
						<h3><a href="<?php echo $article->getUrl();?>"><?php echo $article->getTitle();?></a></h3>
						<div class="subHeader">
							<p><?php echo $article->getPreview(30); ?></p>
							<div id="storyMeta">
								<ul class="metaList">
									<li id="category"><a href="<?php echo $article->getCategory()->getLabel();?>" class="<?php echo $article->getCategory()->getCat();?>"><?php echo $article->getCategory()->getLabel();?></a></li>
									<?php if ($article->getCategory()->getCat() == 'comment') { ?>
									<li id="articleAuthor">
										<?php echo Utility::outputUserList($article->getAuthors()); ?>
									</li>
									<?php } ?>
									<li id="comments"><a href="<?php echo $article->getUrl();?>#commentHeader"><?php $num_comments = $article->getNumComments(); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
								</ul>
							</div>
						</div>
						<?php if ($article->getCategory()->getCat() != 'comment') { ?>
							<div id="secondStoryPic">
								<a href="<?php echo $article->getUrl();?>">
									<?php if ($article->getImage()): ?>
										<img id="secondStoryPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(220, 130); ?>" height="130px" width="220px">
									<?php else: ?>
										<img id="secondStoryPhoto" alt="" src="<?php echo IMAGE_URL.'/220/130/'.DEFAULT_IMG_URI; ?>" height="130px" width="220px">
									<?php endif; ?>
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
						<span><?php echo date('jS',$article->getDate()); ?></span><br/>
						<?php echo date('F Y',$article->getDate()); ?><br/>
					</div>
					<div class="userArticleInfo grid_7 omega second">
						<h3><a href="<?php echo $article->getUrl();?>"><?php echo $article->getTitle();?></a></h3>
						<div class="subHeader">
							<p><?php echo $article->getPreview(30); ?></p>
							<div id="storyMeta">
								<ul class="metaList">
									<li id="category"><a href="<?php echo $article->getCategory()->getLabel();?>" class="<?php echo $article->getCategory()->getCat();?>"><?php echo $article->getCategory()->getLabel();?></a></li>
									<?php if ($article->getCategory()->getCat() == 'comment') { ?>
									<li id="articleAuthor">
										<?php echo Utility::outputUserList($article->getAuthors()); ?>
									</li>
									<?php } ?>
									<li id="comments"><a href="<?php echo $article->getUrl();?>#commentHeader"><?php $num_comments = $article->getNumComments(); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
								</ul>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>			<?php } 
				} else { ?>
				<div class="userArticle">
					<div class="userArticleDate grid_1 alpha">
						<span><?php echo date('jS',$article->getDate()); ?></span><br/>
						<?php echo date('F Y',$article->getDate()); ?><br/>
					</div>
					<div class="userArticleInfo grid_7 omega second">
						<h3><a href="<?php echo $article->getUrl();?>"><?php echo $article->getTitle();?></a></h3>
						<div class="subHeader">
							<p><?php echo $article->getPreview(30); ?></p>
							<div id="storyMeta">
								<ul class="metaList">
									<li id="category"><a href="<?php echo $article->getCategory()->getLabel();?>" class="<?php echo $article->getCategory()->getCat();?>"><?php echo $article->getCategory()->getLabel();?></a></li>
									<?php if ($article->getCategory()->getCat() == 'comment') { ?>
									<li id="articleAuthor">
										<?php echo Utility::outputUserList($article->getAuthors()); ?>
									</li>
									<?php } ?>
									<li id="comments"><a href="<?php echo $article->getUrl();?>#commentHeader"><?php $num_comments = $article->getNumComments(); echo $num_comments.' comment'.($num_comments != 1 ? 's' : '');?></a></li>
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
			<div class="grid_8 clearfix">
				<ul id="pageList" class="clearfix">
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