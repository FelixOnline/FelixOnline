				<div class="section-info-box info-box">
					<?php $theme->render('components/helpers/breadcrumbs', array('origin' => $category, 'type' => 'category')); ?>
					<h1><?php echo $category->getLabel(); ?></h1>
					<?php
					if($category->getEditors()) {
						echo '<p>The '.$category->getLabel().' team is</p>';
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