<?php

$meta = '
	<meta property="og:title" content="'.$user->getName().'"/>
	<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>
	<meta property="og:url" content="'.$user->getURL().'"/>
	<meta property="og:type" content="profile"/>
	<meta property="og:locale" content="en_GB"/>
	<meta property="og:description" content="'.$user->getDescription().'"/>
';
if($user->hasArticlesHiddenFromRobots() && $user->getUser() != "felix" ) {
	$meta .= '<meta name="robots" content="noindex"/>';
}
$header = array(
	'title' => $user->getName().' - '.'Felix Online',
	'meta' => $meta
);

$theme->render('components/globals/header', $header);

?>
	<div class="row full-width">
		<div class="small-12 columns">
			<h1><img src="<?php echo $user->getImage()->getUrl(400,400); ?>" class="headshot" alt="Headshot"><?php echo $user->getName(); ?></h1>
			<div class="header-info-icons">
				<?php if($user->getShowEmail()): ?>
				<a href="mailto:<?php echo $user->getEmail(); ?>"><span class="social social-e-mail"></span>&nbsp;<?php echo $user->getEmail(); ?></a>
				<?php endif; ?>
				<?php if($user->getFacebook()): ?>
				<a href="<?php echo $user->getFacebook(); ?>"><span class="social social-facebook"></span>&nbsp;Facebook</a>
				<?php endif; ?>
				<?php if($user->getTwitter()): ?>
				<a href="http://twitter.com/<?php echo $user->getTwitter(); ?>"><span class="social social-twitter"></span>&nbsp;@<?php echo $user->getTwitter(); ?></a>
				<?php endif; ?>
				<?php if($user->getWebsitename() && $user->getWebsiteurl()): ?>
				<a href="<?php echo $user->getWebsiteurl(); ?>"><span class="glyphicons glyphicons-globe-af"></span>&nbsp;<?php echo $user->getWebsitename(); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="row full-width">
		<div class="small-12 large-9 columns user-articles">
			<?php if ($currentuser->getUser() == $user->getUser()): ?>
				<?php
					$theme->render('components/user/edit_profile', array('user' => $user));
				?>
			<?php endif; ?>
	<!-- End of sidebar -->
		<?php if (!empty($articles)) { ?>
			<?php $theme->render('components/helpers/month_article_view', array(
				'articles' => $articles,
				'show_category' => true,
				'headshot' => false
				)); ?>

			<div class="row">
				<div class="small-12 columns paginator-bit">
					<!-- Page list -->
					<?php $theme->render('components/helpers/pagination', array(
						'pagenum' => $pagenum,
						'class' => $user,
						'pages' => $pages,
						'span' => \FelixOnline\Core\Settings::get('articles_per_user_page'),
						'type' => 'user',
						'key' => $user->getUser()
					)); ?>
					<!-- End of page list -->
				</div>
			</div>
			<input type="hidden" name="token" id="token" value="<?php echo Utility::generateCSRFToken('pagination'); ?>">
			<input type="hidden" name="pag-category" id="pag-category" value="1">
			<input type="hidden" name="pag-headshot" id="pag-headshot" value="0">
		<?php } else { ?>
			<p>Uh oh, <?php echo $user->getFirstName(); ?> has not written any articles for Felix. What a shame!</p>
		<?php } ?>
	</div>
	<div class="small-12 large-3 columns">
		<?php if(($user->getShowLdap() && $data = $user->getInfo()) || $user->getDescription()) { ?>
		<div class="info-box">
			<h1>About <?php echo $user->getFirstName(); ?></h1>
			<?php if($user->getDescription()): ?><p><?php echo nl2br($user->getDescription()); ?></p><?php endif; ?>
			<?php if($user->getShowLdap() && $data = $user->getInfo()): ?>
			<ul>
				<li><b>Course/Title:</b> <?php echo $data[0]; ?></li>
				<li><b>Department:</b> <?php echo $data[2]; ?></li>
			</ul>
		</div>
		<?php endif; ?>
		<?php } ?>
		<?php if ($currentuser->getUser() == $user->getUser()): ?>
			<center><a href="#" data-reveal-id="editProfileModal" class="button small radius">Update your details</a></center>
		<?php endif; ?>

		<?php $theme->render('components/helpers/block_advert', array('sidebar' => true)); ?>

		<?php if ($article_count > 2 && $popular_articles) { ?>
			<div class="info-box trending-block">
				<h1>Most Popular Articles</h1>
				<div class="recent-items-content">
					<ol class="user-popular trending">
					<?php foreach($popular_articles as $article) { ?>
						<li class="user-popular-item">
							<a href="<?php echo $article->getURL();?>">
								<?php echo $article->getTitle();?>
							</a>
						</li>
					<?php } ?>
					</ol>
				</div>
			</div>
		<?php } ?>
		<?php if ($categories) { ?>
			<div class="info-box trending-block">
				<h1><?php echo $user->getFirstName(); ?> Edits</h1>
				<div class="recent-items-content">
					<ul class="user-popular trending">
					<?php foreach($categories as $category) { ?>
						<li class="user-popular-item">
							<a href="<?php echo STANDARD_URL.$category->getCat(); ?>/">
								<b><?php echo $category->getLabel(); ?></b>
							</a>
						</li>
					<?php } ?>
					</ul>
				</div>
				<p>Contact <?php echo $user->getFirstName(); ?> if you would like to get involved in any of these sections of Felix - new contributors always welcome!</p>
			</div>
		<?php } ?>
	</div>
</div>
<?php $theme->render('components/globals/footer'); ?>
