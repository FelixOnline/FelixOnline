<?php
$meta = '
	<meta name="twitter:card" content="summary_large_image"/>
	<meta name="twitter:site" content="@'.(\FelixOnline\Core\Settings::get('app_twitter')).'"/>
	<meta property="og:title" content="'.$article->getTitle().'"/>
	<meta property="og:url" content="'.$article->getURL().'"/>
	<meta property="og:type" content="article"/>
	<meta property="og:locale" content="en_GB"/>
	<meta property="og:description" content="'.$article->getTeaser().'"/>
	<meta property="article:section" content="'.$article->getCategory()->getLabel().'"/>
';

if ($article->getPublished()) {
	$meta .= '<meta property="article:published_time" content="'.date('c', $article->getPublished()).'"/>';
}

foreach ($article->getAuthors() as $author) {
	if($author->getFacebook()) {
		$meta .= '<meta property="article:author" content="'.$author->getFacebook().'"/>';
	}
}

if($article->getImage()) {
	$meta .= '<meta property="og:image" content="'.$article->getImage()->getURL(600).'"/>';
} else {
	$meta .= '<meta property="og:image" content="'.STANDARD_URL . 'img/' . (\FelixOnline\Core\Settings::get('default_img_uri')).'"/>';
}

if(!$article->getSearchable()) {
	$meta .= '<meta name="robots" content="noindex">';
}
$header = array(
	'title' => $article->getTitle().' - '.$article->getCategory()->getLabel().' - '.(\FelixOnline\Core\Settings::get('site_name')),
	'meta' => $meta
);

$theme->render('components/globals/header', $header);

?>

<?php if($article->getVideoUrl()): ?>
	<div class="video-row">
		<div class="row article-row">
			<div class="small-12 columns title-row">
				<div class="article-title-area">
					<h2><?php echo $article->getTitle(); ?></h2>
					<h3><?php echo $article->getTeaser(); ?></h3>
				</div>
				<?php
					try {
						echo '<div class="video-box">'.$article->getVideo().'</div>';
					} catch(\FelixOnline\Exceptions\InternalException $e) {
						echo '<div class="alert-box">Could not load video</div>';
					}
				?>
			</div>
		</div>
	</div>
<?php else: ?>
	<div class="row main-row top-row title-row article-row">
		<div class="small-12 medium-9 medium-push-3 end columns">
			<h2><?php echo $article->getTitle(); ?></h2>
			<h3><?php echo $article->getTeaser(); ?></h3>
		</div>
	</div>
<?php endif; ?>

<div class="row main-row top-row article-row">
	<div class="small-12 medium-3 columns">
			<?php $theme->render('components/article/meta_info', array('article' => $article)); ?>

			<?php $theme->render('components/helpers/block_advert', array('sidebar' => true, 'article' => $article, 'section' => false)); ?>

			<div class="show-for-medium">
				<?php $theme->render('components/article/topics', array('topics' => $topics)); ?>

				<?php $theme->render('components/article/meta_share', array('article' => $article, 'hidetitle' => false)); ?>
			</div>
	</div>

	<div class="small-12 medium-9 large-7 end columns">
	<?php $theme->render('components/article/article_content'); ?>
	</div>
</div>

<div class="row <?php echo $article->getCategory()->getCat(); ?>">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="bar-text" id="commentHeader">Have your say</div>
	</div>
</div>
<div class="row top-row main-row">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<?php $theme->render('components/article/comment_form'); ?>
	</div>
	<input type="hidden" name="poll-token" id="poll-token" value="<?php echo Utility::generateCSRFToken('poll_vote'); ?>"/>

	<?php $theme->render('components/modals/box_abuse'); ?>
	<?php $theme->render('components/modals/box_comment_policy'); ?>
	<!-- End of comments -->
</div>

<?php if(count($topics) > 0): ?>
<div class="row <?php echo $article->getCategory()->getCat(); ?>">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="bar-text">More on this</div>
	</div>
</div>
<div class="row top-row main-row">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="row small-up-12 medium-up-2" data-equalizer data-equalize-on="medium">
			<?php
				foreach($topics as $topic) {
			?>
				<div class="columns" data-equalizer-watch>
					<?php $theme->render('components/article/topic_block', array('topic' => $topic)); ?>
				</div>
			<?php
				}
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row <?php echo $article->getCategory()->getCat(); ?>">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="bar-text">Trending stories</div>
	</div>
</div>
<div class="row top-row main-row">
	<div class="small-12 medium-9 medium-push-3 large-7 end columns">
		<div class="row small-up-12 medium-up-2">
			<?php
				$viewed_articles = (new \FelixOnline\Core\ArticleManager())->getMostPopular(\FelixOnline\Core\Settings::get('popular_articles'));
				
				if (!is_null($viewed_articles)) {
					foreach($viewed_articles as $article) {
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
				} else {
			?>

			<p>It doesn't look like any articles have been read recently...</p>

			<?php
				}
			?>
		</div>
	</div>
</div>

<?php $theme->render('components/globals/footer'); ?>
