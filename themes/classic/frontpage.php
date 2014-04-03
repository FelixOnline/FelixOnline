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
	<!-- Sidebar -->
	<div class="sidebar grid_4 push_8">
		<?php
			$theme->render('sidebar/fbLikeBox');
			$theme->render('sidebar/mediaBox');
			$theme->render('sidebar/socialLinks');
			$theme->render('sidebar/fbActivity');
			$theme->render('sidebar/mostPopular');
			$theme->render('sidebar/iscience');
			$theme->render('sidebar/recentcomments');
		?>
	</div>
	<?php $timing->log('after sidebar'); ?>
	<!-- End of sidebar -->
	<!-- Front page articles -->
	<div class="grid_8 pull_4 featCont layout1">
		<?php
			// Start caching
			$cache = new Cache('frontpage');
			if($cache->start()) {
		?>
		<?php
			// Section a
			$sectionA = $frontpage->getSection('a');
			$timing->log('get frontpage articles');
			if (!is_null($sectionA)) {
		?>
		<!-- Top story -->
		<div class="grid_8 alpha topstory">
			<?php // Initialise top story
				$article = $sectionA['one'];
				$timing->log('initialise article');
			?>
			<div class="border clearfix <?php echo $article->getCategory()->getCat();?>">
				<h2>
					<a href="<?php echo $article->getURL(); ?>">
						<?php echo $article->getTitle(); ?>
					</a>
				</h2>
				<div class="subHeader">
					<p>
						<?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(50); ?>
					</p>
					<div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra'; ?>">
						<ul class="metaList">
							<?php if($article->getNumComments()) { ?>
								<li id="comments">
									<a href="<?php echo $article->getURL();?>#commentHeader">
										<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
									</a>
								</li>
							<?php } ?>
							<li id="category">
								<a href="<?php echo $article->getCategory()->getURL();?>" class="<?php echo $article->getCategory()->getCat();?>">
									<?php echo $article->getCategory()->getLabel();?>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div id="topStoryPic">
					<a href="<?php echo $article->getURL();?>">
						<?php if ($article->getImage()): ?>
							<img id="topStoryPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(340, 220); ?>" height="220px" width="340px">
						 <?php else: ?>
							<img id="topStoryPhoto" alt="" src="<?php echo IMAGE_URL.'340/220/'.DEFAULT_IMG_URI; ?>" height="220px" width="340px">
						<?php endif; ?>
					</a>
				</div>
			</div>
		</div>
		<!-- End of top story -->
		<?php } ?>
		<!-- In this issue -->
		<div class="grid_2 push_6 alpha omega thisIssue">
			<h5>In this Issue</h5>
			<?php
				// Section b
				$sectionB = $frontpage->getSection('b');

				if (!is_null($sectionB)):
				foreach($sectionB as $key => $article) { ?>
					<div class="thisIssueCont <?php if($key == 'one') echo 'top';?>">
						<a href="<?php echo $article->getURL();?>">
						<?php if ($article->getImage()): ?>
							<img alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(140, 140);?>" width="140px" height="140px" class="captify" rel="caption2"/>
						 <?php else: ?>
							<img alt="" src="<?php echo IMAGE_URL.'140/140/'.DEFAULT_IMG_URI; ?>" width="140px" height="140px" class="captify" rel="caption2"/>
						<?php endif; ?>
							<br class="c"/>
						</a>
						<div class="caption1">
							<a href="<?php echo $article->getURL();?>">
								<?php echo $article->getShortTitle();?>
							</a>
						</div>
						<div id="caption2">
							<a href="<?php echo $article->getURL();?>">
								<?php echo $article->getTeaser(); ?>
							</a>
						</div>
					</div>
				<?php }
				else :
				?>
				<div class="thisIssueCont top">
					No articles have been assigned to this zone
				</div>
				<?php endif; ?>
		</div>
		<!-- End of in this issue -->

		<?php if (!is_null($sectionA)) { ?>
		<!-- Second article -->
		<?php $article = $sectionA['two']; ?>
		<div class="grid_6 pull_2 omega alpha featBox <?php echo $article->getCategory()->getCat();?>">
			<h3>
				<a href="<?php echo $article->getURL();?>">
					<?php echo $article->getTitle();?>
				</a>
			</h3>
			<div class="subHeader">
				<p>
					<?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(20); ?>
				</p>
				<div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra'; ?>">
					<ul class="metaList">
						<?php if($article->getNumComments()) { ?>
							<li id="comments">
								<a href="<?php echo $article->getURL();?>#commentHeader">
									<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
								</a>
							</li>
						<?php } ?>
						<li id="category">
							<a href="<?php echo $article->getCategory()->getURL();?>" class="<?php echo $article->getCategory()->getCat();?>">
								<?php echo $article->getCategory()->getLabel();?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div id="secondStoryPic">
				<a href="<?php echo $article->getURL(); ?>">
					<?php if ($article->getImage()): ?>
						<img id="secondStoryPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(220, 160); ?>" height="160px" width="220px">
					<?php else: ?>
						<img id="secondStoryPhoto" alt="" src="<?php echo IMAGE_URL.'220/160/'.DEFAULT_IMG_URI; ?>" height="160px" width="220px">
					<?php endif; ?>
				</a>
			</div>
		</div>
		<!-- End of second article -->

		<!-- Third article -->
		<?php $article = $sectionA['three']; ?>
		<div class="grid_6 pull_2 omega alpha featBox <?php echo $article->getCategory()->getCat();?>" id="last">
			<h3>
				<a href="<?php echo $article->getURL();?>">
					<?php echo $article->getTitle();?>
				</a>
			</h3>
			<div class="subHeader">
				<p>
					<?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(20); ?>
				</p>
				<div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra'; ?>">
					<ul class="metaList">
						<?php if($article->getNumComments()) { ?>
							<li id="comments">
								<a href="<?php echo $article->getURL();?>#commentHeader">
									<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
								</a>
							</li>
						<?php } ?>
						<li id="category">
							<a href="<?php echo $article->getCategory()->getURL();?>" class="<?php echo $article->getCategory()->getCat();?>">
								<?php echo $article->getCategory()->getLabel();?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div id="secondStoryPic">
				<a href="<?php echo $article->getURL();?>">
					<?php if ($article->getImage()): ?>
						<img id="secondStoryPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(220, 160); ?>" height="160px" width="220px">
					<?php else: ?>
						<img id="secondStoryPhoto" alt="" src="<?php echo IMAGE_URL.'220/160/'.DEFAULT_IMG_URI; ?>" height="160px" width="220px">
					<?php endif; ?>
				</a>
			</div>
		</div>
		<!-- End of third article -->

		<!-- Article four and five -->
		<?php
			$articleA = $sectionA['four'];
			$articleB = $sectionA['five'];
		?>
		<div class="grid_6 pull_2 alpha omega featBox bottom">
			<!-- Header -->
			<div class="grid_3 alpha header <?php echo $articleA->getCategory()->getCat();?>">
				<a href="<?php echo $articleA->getCategory()->getURL();?>" class="cat <?php echo $articleA->getCategory()->getCat();?>">
					<?php echo $articleA->getCategory()->getLabel();?>
				</a>
				<h4>
					<a href="<?php echo $articleA->getURL();?>">
						<?php echo $articleA->getTitle();?>
					</a>
				</h4>
			</div>
			<div class="grid_3 omega header <?php echo $articleB->getCategory()->getCat();?>">
				<a href="<?php echo $articleB->getCategory()->getURL();?>" class="cat <?php echo $articleB->getCategory()->getCat();?>">
					<?php echo $articleB->getCategory()->getLabel();?>
				</a>
				<h4>
					<a href="<?php echo $articleB->getURL();?>">
						<?php echo $articleB->getTitle();?>
					</a>
				</h4>
			</div>
			<div class="clear"></div>

			<!-- Pictures -->

			<div id="thirdStoryPic" class="grid_3 alpha">
				<a href="<?php echo $articleA->getURL();?>">
					<?php if ($articleA->getImage()): ?>
						<img id="thirdStoryPhoto" alt="<?php echo $articleA->getImage()->getTitle();?>" src="<?php echo $articleA->getImage()->getURL(210, 130);?>" width="210px" height="130px">
					<?php else: ?>
						<img id="thirdStoryPhoto" alt="" src="<?php echo IMAGE_URL.'210/130/'.DEFAULT_IMG_URI; ?>" height="130px" width="210px">
					<?php endif; ?>
				</a>
			</div>
			<div id="thirdStoryPic" class="grid_3 omega">
				<a href="<?php echo $articleB->getURL();?>">
					<?php if ($articleB->getImage()): ?>
						<img id="thirdStoryPhoto" alt="<?php echo $articleB->getImage()->getTitle();?>" src="<?php echo $articleB->getImage()->getURL(210, 130);?>" width="210px" height="130px">
					<?php else: ?>
						<img id="thirdStoryPhoto" alt="" src="<?php echo IMAGE_URL.'210/130/'.DEFAULT_IMG_URI; ?>" height="130px" width="210px">
					<?php endif; ?>
				</a>
			 </div>
			<div class="clear"></div>

			<!-- Teaser -->
			<p class="grid_3 alpha">
				<?php if($articleA->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($articleA->getAuthors()).':</b> '; endif; echo $articleA->getPreview(25); ?>
			</p>
			<p class="grid_3 omega">
				<?php if($articleB->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($articleB->getAuthors()).':</b> '; endif; echo $articleB->getPreview(25); ?>
			</p>
			<div class="clear"></div>

			<!-- Story Meta -->
			<div id="storyMeta" class="grid_3 alpha <?php if(!$articleA->getNumComments()) echo 'extra';?>">
				<ul class="metaList">
					<?php if($articleA->getNumComments()) { ?>
						<li id="comments">
							<a href="<?php echo $articleA->getURL();?>#commentHeader">
								<?php echo $articleA->getNumComments().' comment'.($articleA->getNumComments() != 1 ? 's' : '');?>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div id="storyMeta" class="grid_3 omega <?php if(!$articleB->getNumComments()) echo 'extra';?>">
				<ul class="metaList">
					<?php if($articleB->getNumComments()) { ?>
						<li id="comments">
							<a href="<?php echo $articleB->getURL();?>#commentHeader">
								<?php echo $articleB->getNumComments().' comment'.($articleB->getNumComments() != 1 ? 's' : '');?>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
		<!-- End of article four and five -->

		<!-- News list -->
		<div class="grid_6 pull_2 alpha omega newsList">
			<ul>
				<?php $article = $sectionA['six']; ?>
				<li class="<?php echo $article->getCategory()->getCat();?>">
					<h4>
						<a href="<?php echo $article->getURL();?>" id="title">
							<?php echo $article->getTitle();?>
						</a> <a href="<?php echo $article->getCategory()->getURL();?>" class="<?php echo $article->getCategory()->getCat();?>">
							<span id="category">
								<?php echo $article->getCategory()->getLabel();?>
							</span>
						</a>
					</h4>
					<p>
						<?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(15); ?>
					</p>
				</li>

				<?php $article = $sectionA['seven']; ?>
				<li class="<?php echo $article->getCategory()->getCat();?>">
					<h4>
						<a href="<?php echo $article->getURL();?>" id="title">
							<?php echo $article->getTitle();?>
						</a> <a href="<?php echo $article->getCategory()->getURL();?>" class="<?php echo $article->getCategory()->getCat();?>">
							<span id="category">
								<?php echo $article->getCategory()->getLabel();?>
							</span>
						</a>
					</h4>
					<p>
						<?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(15); ?>
					</p>
				</li>

				<?php $article = $sectionA['eight']; ?>
				<li class="<?php echo $article->getCategory()->getCat();?>">
					<h4>
						<a href="<?php echo $article->getURL();?>" id="title">
							<?php echo $article->getTitle();?>
						</a> <a href="<?php echo $article->getCategory()->getURL();?>" class="<?php echo $article->getCategory()->getCat();?>">
							<span id="category">
								<?php echo $article->getCategory()->getLabel();?>
							</span>
						</a>
					</h4>
					<p>
						<?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(15); ?>
					</p>
				</li>
			</ul>
		</div>
		<!-- End of news list -->
		<?php } else { ?>
			<div class="grid_8 alpha">No articles have been set on the frontpage</div>
		<?php } ?>

		<!-- Featured articles -->
		<div class="grid_8 alpha omega" id="featuredarticles">
			<h3>Featured Articles</h3>
			<?php
				// Featured articles
				$featured = $frontpage->getSection('featured');
			?>

			<!-- Main featured article -->
			<?php if (!is_null($featured)) { ?>
			<?php $article = $featured['one']; ?>
			<a href="<?php echo $article->getURL(); ?>">
				<div id="imgcont">
					<?php if ($article->getImage()): ?>
						<img alt="<?php echo $article->getImage()->getTitle();?>" src="<?php echo $article->getImage()->getURL(290, 190);?>" width="290px">
					<?php else: ?>
						<img alt="" src="<?php echo IMAGE_URL.'290/190/'.DEFAULT_IMG_URI; ?>" width="290px">
					<?php endif; ?>
				</div>
				<h4>
					<?php echo $article->getTitle();?>
				</h4>
			</a>
			<br/>
			<span><?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(15); ?></span>
			<ul>
				<li>
					Other Articles:
				</li>
				<li>
					<?php $article = $featured['two']; ?>
					<a href="<?php echo $article->getURL(); ?>">
						<?php echo $article->getTitle();?>
					</a>
				</li>
				<li>
					<?php $article = $featured['three']; ?>
					<a href="<?php echo $article->getURL(); ?>">
						<?php echo $article->getTitle();?>
					</a>
				</li>
			</ul>
			<?php } else { ?>
				<p>No articles have been assigned to this zone</p>
			<?php } ?>
		</div>
		<!-- End of featured articles -->

		<?php } $cache->stop(); ?>

		<?php $timing->log('end of frontpage articles'); ?>
		<!-- Editorial -->
		<div class="grid_4 alpha commentBox">
			<div class="border">
				<h4>Editorial</h4>
					<?php
						$editorial = $frontpage->getEditorial();
					if (!is_null($editorial)) { ?>
					<h3>
						<a href="<?php echo $editorial->getURL(); ?>">
							<?php echo $editorial->getTitle();?>
						</a>
					</h3>
					<p>
						<?php echo $editorial->getPreview(245); ?> ...
					</p>
					<?php } else { ?>
					<p>No editorial could be found in the database</p>
					<?php } ?>
			</div>
		</div>
		<!-- End of editorial -->

		<?php $timing->log('end of editorial'); ?>

		<div class="grid_4 omega">
			<div class="twitterbox">
				<h4>Twitter</h4>
				<div id="twitheader">
					<a href="http://twitter.com/feliximperial" title="Felix Imperial"><img src="img/felixtwitter.jpg" width="50px" id="felixTwitterlogo"/></a>
					<h5>Felix Imperial</h5>
					<p><a href="http://twitter.com/feliximperial" target="_blank" title="Felix Twitter account">@feliximperial</a> - South Kensington</p>
				</div>
				<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/feliximperial"  data-widget-id="346347929105219584" data-chrome="noheader nofooter noborders noscrollbar transparent" data-tweet-limit="4">Tweets by @feliximperial</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>

			<?php $timing->log('end of twitter'); ?>

			<div id="felixinfo">
				<h3>About Us</h3>
				<p>Felix is the award winning student newspaper of Imperial College London since 1949. Bringing you the best of news and commentary every week.</p>
				<p>If you would like to get involved or ask us a question then feel free to <a href="contact/">contact us</a></p>
			</div>
		</div>
	</div>
	<!-- End of front page articles -->
	<?php $timing->log('end of frontpage'); ?>
</div>

<!-- Featured bar -->
<div class="container_12 clearfix">
	<?php
	$cats = (new \FelixOnline\Core\CategoryManager())
		->filter('active = 1')
		->filter('hidden = 0')
		->filter('id > 0')
		->filter('`order` > 0')
		->order('order', 'ASC')
		->values();
	if (!is_null($cats)) {
		foreach($cats as $key => $cat) {
			$article = $cat->getTopSlider_1();
			if ($article) { ?>
				<div class="grid_3 featuredBar <?php if (($key+1) % 4 == 0) echo 'last';?>">
					<div class="border <?php echo $cat->getCat();?>">
						<h3>
							<a href="<?php echo STANDARD_URL.$cat->getCat();?>/">
								<?php echo $cat->getLabel();?>
							</a>
						</h3>
						<a href="<?php echo $article->getURL();?>">
							<?php if ($article->getImage()): ?>
								<img id="featuredBarPhoto" alt="<?php echo $article->getImage()->getTitle(); ?>" src="<?php echo $article->getImage()->getURL(220, 120);?>" width="220px" height="120px">
							<?php else: ?>
								<img id="featuredBarPhoto" alt="" src="<?php echo IMAGE_URL.'220/120/'.DEFAULT_IMG_URI; ?>" width="220px" height="120px">
							<?php endif; ?>
						</a>
						<h4>
							<a href="<?php echo $article->getURL();?>">
								<?php echo $article->getTitle();?>
							</a>
						</h4>
						<p>
							<?php if($article->getCategory()->getCat() == 'comment'): echo '<b>'.Utility::outputUserList($article->getAuthors()).':</b> '; endif; echo $article->getPreview(10); ?>
						</p>
					</div>
				</div>
			<?php }
		}
	} ?>
</div>

<!-- End of featured bar -->
<?php $theme->render('footer'); ?>
