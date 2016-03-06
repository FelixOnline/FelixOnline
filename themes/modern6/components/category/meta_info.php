<div class="section-info-box info-box info-title-only <?php echo $category->getCat(); ?>">
	<h1><?php echo $category->getLabel(); ?></h1>
</div>

<?php if(!$hide_extra): ?>
<div class="info-secondary-box pad">
	<?php
	if($category->getEditors()) {
		echo '<p>'.$category->getLabel().' is brought to you by</p>';
		foreach($category->getEditors() as $author) {
			$theme->render('components/article/meta_author', array('user' => $author));
		}
	} else {
		echo '<p>Nobody edits this section. Why not get involved?</p>';
	}
	?>
	<?php if($category->getTwitter() || $category->getEmail()): ?>
	<div class="contact-area">
		<?php if($category->getEmail()): ?>
		<p><a href="mailto:<?php echo $category->getEmail(); ?>"><span class="social social-e-mail"></span> <?php echo $category->getEmail(); ?></a></p>
		<?php endif; ?>
		<?php if($category->getTwitter()): ?>
		<p><a href="http://twitter.com/<?php echo $category->getTwitter(); ?>"><span class="social social-twitter"></span> @<?php echo $category->getTwitter(); ?></a></p>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>
<?php endif; ?>