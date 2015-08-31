<div class="row">
	<div class="small-2 medium-4 large-3 columns">
		<center><img class="article-user-pic" alt="<?php echo $user->getName(); ?>" src="<?php echo $user->getImage()->getURL(100, 100); ?>"></center>
	</div>
	<div class"small-10 medium-8 large-9 columns">
		<b><a href="<?php echo $user->getURL(); ?>"><?php echo $user->getName(); ?></a></b><br>
		<?php if($user->getTwitter()): echo '<a href="https://twitter.com/'.$user->getTwitter().'" class="twitter-follow-button" data-show-count="false" data-dnt="true">Follow @'.$user->getTwitter().'</a>'; endif; ?>
	</div>
</div>