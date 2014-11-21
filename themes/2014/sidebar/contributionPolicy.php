			<?php if(!isset($category)): ?>
				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>Write for <i>Felix</i></h3>
				</div>
				<div class="felix-contribute">
					<p>Interested in becoming a news reporter? Or just have a favourite something to share with Imperial? Write for Felix &mdash; it's easy!</p>
					<p>Got a tip you'd like to share? We welcome anonymous messages too.</p>
					<center><a class="button small" href="<?php echo STANDARD_URL; ?>contribute/">Find out how to contribute</a></center>
				</div>
			<?php else: ?>
				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>Write for <?php echo $category->getLabel(); ?></h3>
				</div>
				<div class="felix-contribute">
					<p>Want to write for this section? Drop the editors a line via the contact details above and they'll let you how to get involved - contributors always welcome!</p>
				</div>
			<?php endif; ?>