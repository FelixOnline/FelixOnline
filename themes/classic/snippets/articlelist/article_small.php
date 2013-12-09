<div class="featBox">
	<div class="border clearfix">
		<h3>
			<a href="<?php echo $article->getURL();?>">
				<?php echo $article->getTitle();?>
			</a>
		</h3>
		<div class="subHeader <?php if(!$article->getImage()) { echo "wide"; } else if($article->getImage()->isTall(160, 160)) { echo ' tallpic'; }?>">
			<p>
				<?php 
					if(!$article->getImage()) { 
						echo $article->getPreview(60);
					} else {
						echo $article->getPreview(30);
					}
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
			<div id="thirdStoryPic">
				<a href="<?php echo $article->getURL();?>">
				<?php if($image->isTall(160, 160)) { ?>
					<img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(100, 120);?>">
				<?php } else { ?>
					<img id="topStoryPhoto" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(160, 120);?>">
				<?php } ?>
				</a>
			</div>
		<?php } ?>
	</div>
</div>
