<div class="row full-width editor-icons collapse">
	<div class="small-1 large-2 columns">
		<center><img class="article-user-pic" alt="<?php echo $user->getName(); ?>" src="<?php echo $user->getImage()->getURL(100, 100); ?>"></center>
	</div>
	<div class="small-11 large-10 columns">
		<p>
			<a href="<?php echo $user->getURL(); ?>"><?php echo $user->getName(); ?></a>
			<?php if($user->getTwitter()): echo '<a href="https://twitter.com/'.$user->getTwitter().'"><span class="social social-twitter"></span> @'.$user->getTwitter().'</a>'; endif; ?>
		</p>
	</div>
</div>