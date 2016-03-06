<div class="row editor-icons">
	<div class="small-12 columns">
		<img class="article-user-pic" alt="<?php echo $user->getName(); ?>" src="<?php echo $user->getImage()->getURL(100, 100); ?>">
		<span class="authors">
			<a href="<?php echo $user->getURL(); ?>"><?php echo $user->getName(); ?></a>
		</span>
	</div>
</div>