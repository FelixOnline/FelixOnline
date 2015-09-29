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
						echo '<p>Nobody edits this section. Why not you?</p>';
					}
					?>
					<p>Contact <?php echo $category->getLabel(); ?></p>
					<div class="contact-area">
						<div class="row">
							<div class="small-2 columns">
								<a href="mailto:<?php echo $category->getEmail(); ?>"><span class="social social-e-mail"></span></a>
							</div>
							<div class="small-10 columns">
								<p><a href="mailto:<?php echo $category->getEmail(); ?>"><?php echo $category->getEmail(); ?></a></p>
							</div>
						</div>
						<?php if($category->getTwitter()): ?>
						<div class="row">
							<div class="small-2 columns">
								<a href="http://twitter.com/<?php echo $category->getTwitter(); ?>"><span class="social social-twitter"></span></a>
							</div>
							<div class="small-10 columns">
								<p><a href="http://twitter.com/<?php echo $category->getTwitter(); ?>">@<?php echo $category->getTwitter(); ?></a></p>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>