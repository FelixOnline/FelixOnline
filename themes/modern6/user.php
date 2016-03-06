<?php

$meta = '
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:title" content="'.$user->getName().'"/>
	<meta property="og:image" content="'.$user->getImage()->getUrl(400,400).'"/>
	<meta property="og:url" content="'.$user->getURL().'"/>
	<meta property="og:type" content="profile"/>
	<meta property="og:locale" content="en_GB"/>
	<meta property="og:description" content="'.$user->getDescription().'"/>
';
if($user->hasArticlesHiddenFromRobots() && $user->getUser() != "felix" ) {
	$meta .= '<meta name="robots" content="noindex"/>';
}

$header = array(
	'title' => $user->getName().' - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => $meta
);

$theme->render('components/globals/header', $header);

?>
	<div class="row main-row top-row">
		<div class="small-12 medium-9 columns">
			<h1><img src="<?php echo $user->getImage()->getUrl(400,400); ?>" class="headshot" alt="Headshot"><?php echo $user->getName(); ?></h1>
			<div class="header-info-icons">
				<?php if($user->getShowLdap() && $data = $user->getInfo()): ?>
				<p><?php if($data[1] != ''): echo $data[1].' in '; endif; ?> <?php echo $data[2]; ?> (<?php echo $data[0]; ?>)</p>
				<?php endif; ?>

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
		<div class="small-12 medium-3 columns">
			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true)); ?>
		</div>
	</div>

	<?php if ($article_count > 2 && $popular_articles) { ?>
		<div class="row news">
			<div class="small-12 columns">
				<div class="bar-text"><?php echo $user->getFirstName(); ?>'s top articles</div>
			</div>
		</div>

		<div class="row main-row top-row small-up-1 medium-up-3 large-up-3">
			<?php
				foreach($popular_articles as $article) {
					$i++;
					?>
					<div class="columns">
					<?php
						$theme->render('components/category/block_normal', array(
							'article' => $article,
							'show_category' => true,
							'headshot' => false
						));
					?>
					</div>
					<?php
				}
			?>
		</div>
	<?php } ?>

	<div class="row main-row">
		<div class="small-12 columns user-articles">
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
	</div>
<?php $theme->render('components/globals/footer'); ?>
