			<?php if(!isset($category)): ?>
				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>write for us</h3>
				</div>
				<p>Interested in becoming a news reporter? Or just have a favourite something to share with Imperial? Write for Felix - it's easy!</p>
				<p>Got a tip you'd like to share? We welcome anonymous messages too.</p>
				<center><a class="button" href="<?php echo STANDARD_URL; ?>issuearchive/">Find out how to contribute</a></center>
			<?php else: ?>
				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h3>write for <?php echo $category->getLabel(); ?></h3>
				</div>
				<p><b>TO BE IMPLEMENTED!!!</b></p>
			<?php endif; ?>