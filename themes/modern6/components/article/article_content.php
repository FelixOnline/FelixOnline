<?php $image = $article->getImage(); ?>
<?php if($image && !$article->getVideoUrl()) { ?>
	<div class="article-image<?php if($image->isTall()) { ?> tall-image<?php } ?>">
		<div class="article-image-image sizey-image" data-width="<?php echo $image->getWidth(); ?>" data-height="<?php echo $image->getHeight(); ?>">
		<?php if($image->isTall()) { ?>
			<img class="vertical" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(750);?>">
		<?php } else { ?>
			<img class="horizontal" alt="<?php echo $image->getTitle();?>" src="<?php echo $image->getURL(1280);?>">
		<?php } ?>
		</div>

		<?php if ( $article->getImgCaption() || $image->getAttribution()) { ?>
			<div class="article-image-subcaption">
				<?php if ($article->getImgCaption()) { ?> 
					<?php echo $article->getImgCaption();?>
				<?php } ?>
				<?php if($image->getAttribution()) { ?>
					<div class="imageAttr">
						<span>
						<?php if($image->getAttrLink()) { ?>
							<a href="<?php echo $image->getAttrLink(); ?>">
						<?php } ?>
						Credit: <?php echo $image->getAttribution();?>
						<?php if($image->getAttrLink()) echo '</a>'?>
						</span>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<?php if($polls): ?>
	<?php foreach($polls as $poll): ?>
		<?php $theme->render('components/article/main_poll', array('poll' => $poll, 'bottom' => false)); ?>
	<?php endforeach; ?>
<?php endif; ?>

<div class="article-text">
<?php
	echo $text;
?>
</div>

<?php if($polls): ?>
	<?php foreach($polls as $poll): ?>
		<?php $theme->render('components/article/main_poll', array('poll' => $poll, 'bottom' => true)); ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if(!$article->getBlog()): ?>
	<?php $theme->render('components/article/meta_share', array('article' => $article, 'hidetitle' => false)); ?>
<?php endif; ?>

<br>