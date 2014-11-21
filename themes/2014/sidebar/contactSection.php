				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>Contact <?php echo $category->getLabel(); ?></h3>
				</div>
				<div class="felix-contact-area">
					<div class="row felix-contact-row">
						<div class="small-3 columns">
							<a href="mailto:<?php echo $category->getEmail(); ?>"><img src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/email.png"></a>
						</div>
						<div class="small-9 columns">
							<p><a href="mailto:<?php echo $category->getEmail(); ?>"><?php echo $category->getEmail(); ?></a></p>
						</div>
					</div>
					<?php if($category->getTwitter()): ?>
					<div class="row felix-contact-row">
						<div class="small-3 columns">
							<a href="http://twitter.com/<?php echo $category->getTwitter(); ?>"><img src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/twitter.png"></a>
						</div>
						<div class="small-9 columns">
							<p><a href="http://twitter.com/<?php echo $category->getTwitter(); ?>">@<?php echo $category->getTwitter(); ?></a></p>
						</div>
					</div>
					<?php endif; ?>
					<div class="row felix-contact-row">
						<div class="small-3 columns">
							<a href="<?php echo STANDARD_URL.'rss/'.$category->getCat(); ?>"><img src="<?php echo STANDARD_URL.'themes/'.THEME_NAME.'/'; ?>img/rss.png"></a>
						</div>
						<div class="small-9 columns">
							<p><a href="<?php echo STANDARD_URL.'rss/'.$category->getCat(); ?>">RSS Feed</a></p>
						</div>
					</div>
				</div>