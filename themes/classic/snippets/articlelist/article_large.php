<!-- Top story -->
<div class="topstory">
	<div class="border clearfix">
		<h2>
			<a href="<?php echo $article->getURL();?>">
				<?php echo $article->getTitle();?>
			</a>
		</h2>
		<div class="subHeader <?php if($article->getImage() && $article->getImage()->isTall(300, 300)) { echo ' tallpic'; }?>">
			<p>
				<?php 
					echo $article->getPreview(50);
				?>
			</p>
			<div id="storyMeta">
				<ul class="metaList">
					<?php if ($article->getCategory()->getCat() == 'comment') { ?>
					<li id="articleAuthor">
						<?php echo Utility::outputUserList($article->getAuthors()); ?>
					</li>
					<?php } ?>
					<?php if($article->getNumComments()) { ?>
						<li id="comments">
							<a href="<?php echo $article->getURL();?>#commentHeader">
								<?php echo $article->getNumComments().' comment'.($article->getNumComments() != 1 ? 's' : '');?>
							</a>
						</li>
					<?php } ?>
					<li>
						<?php echo date("l F j, Y",$article->getDate());?>
					</li>
				</ul>
			</div>
		</div>
		<?php if ($image = $article->getImage()) { ?>
			<div id="topStoryPic">
				<a href="<?php echo $article->getURL();?>">
				<?php if($image->isTall(300, 300)) { ?>
					<img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(150, 180);?>">
				<?php } else { ?>
					<img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(300, 180);?>">
				<?php } ?>
				</a>
			</div>
		<?php } else { ?>
			<div id="topStoryPic">
				<a href="<?php echo $article->getURL();?>">
					<img id="topStoryPhoto" alt="" src="<?php echo IMAGE_URL.'300/180/'.DEFAULT_IMG_URI; ?>">
				</a>
			</div>
		<?php } ?>
	</div>
</div>
<!-- End of top story -->
