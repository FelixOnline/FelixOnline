<div class="featBox">
	<div class="border clearfix">
		<h3>
			<a href="<?php echo $article->getURL();?>">
				<?php echo $article->getTitle();?>
			</a>
		</h3>
		<div class="subHeader <?php if($article->getImage() && $article->getImage()->isTall(160, 160)) { echo ' tallpic'; }?>">
			<p>
				<?php 
					echo $article->getPreview(35);
				?>
			</p>
			<div id="storyMeta" class="<?php if(!$article->getNumComments()) echo 'extra';?>">
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
			<div id="secondStoryPic">
				<a href="<?php echo $article->getURL();?>">
				<?php if($image->isTall(220, 220)) { ?>
					<img id="secondStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(120, 130);?>">
				<?php } else { ?>
					<img id="secondStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(220, 120);?>">
				<?php } ?>
				</a>
			</div>
		<?php } else { ?>
			<div id="secondStoryPic">
				<a href="<?php echo $article->getURL();?>">
					<img id="secondStoryPhoto" alt="" src="<?php echo IMAGE_URL.'220/120/'.DEFAULT_IMG_URI; ?>">
				</a>
			</div>
		<?php } ?>
	</div>
</div>
