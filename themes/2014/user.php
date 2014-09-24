<?php
$timing->log('user page');

$meta = '
	<meta property="og:title" content="'.$user->getName().'"/>
	<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>
	<meta property="og:url" content="'.$user->getURL().'"/>
	<meta property="og:type" content="profile"/>
	<meta property="og:locale" content="en_GB"/>
	<meta property="og:description" content="'.$user->getDescription().'"/>
';
if($user->hasArticlesHiddenFromRobots() && $user->getUser!="felix" ) {
	$meta .= '<meta name="robots" content="noindex"/>';
}
$header = array(
	'title' => $user->getName().' - '.'Felix Online',
	'meta' => $meta
);

$theme->render('components/header', $header);
?>
		<div class="user-title">
			<div class="row">
				<div class="medium-6 columns">
					<h1><?php echo $user->getName(); ?></h1>
				</div>
				<div class="medium-6 columns">
					<div class="user-meta text-right show-for-medium-up">
					<?php if ($articles || $comments) { ?>
						<?php if ($articles){ echo 'Author of <b>'.$article_count; ?></b> article<?php echo ($article_count != 1 ? 's' : '');?><?php } ?>
						<?php if($comments && $articles){?>and <?php } ?>  
						<?php if($comments) { echo '<b>'.$comment_count;?></b> comment<?php echo ($comments != 1 ? 's' : ''); }?> 
						since <b><?php echo date('d/m/Y',$user->getFirstLogin());?></b>
						<br>
					<?php } ?>

					<div class="user-email" <?php if(!$user->getEmail()) echo 'style="display:none;"'; ?>>Contact <?php echo $user->getName(); ?>: <b><?php echo Utility::hideEmail($user->getEmail()); ?></b></div>
					</div>
				</div>
			</div>
		</div>
	<div class="row">
		<div class="medium-8 columns">

	<!-- End of sidebar -->
		<?php if (!empty($articles)) { ?>
			<!-- Articles -->
				<div class="felix-item-title felix-item-title felix-item-title-generic">
					<h2>
						articles
					</h2>
				</div>
				<?php foreach($articles as $key => $article) {
					$theme->render('components/articlelist/article_medium', array(
						'article' => $article
					));
				} ?> 
			<!-- End of articles -->
			
			<!-- Page list -->
			<?php $theme->render('components/pagination', array(
				'pagenum' => $pagenum,
				'class' => $user,
				'pages' => $pages,
				'span' => ARTICLES_PER_USER_PAGE
			)); ?>
			<!-- End of page list -->
		<?php } ?>
	</div>
	<div class="medium-4 columns">
		<?php if ($article_count > 2 && $popular_articles) { ?>
			<div class="felix-item-title felix-item-title felix-item-title-generic">
				<h3>most popular articles</h3>
			</div>
				<ol id="userPopular">
				<?php foreach($popular_articles as $article) { ?>
					<li id="userPopList">
						<div id="popTitle">
							<?php if($currentuser->isLoggedIn() == $user->getUser()) { ?>
							<div id="popHits">
								<?php echo $article->getHits(); ?> hits
							</div>
							<?php } ?>
							<a href="<?php echo $article->getURL();?>"><?php echo $article->getTitle();?></a>
						</div>
					</li>
				<?php } ?>
				</ol>
		<?php } ?>
		<?php if ($comments) { ?>
			<div class="felix-item-title felix-item-title felix-item-title-generic">
				<h3>recent comments</h3>
			</div>
			<div id="recentComments">
				<?php if ($popularity = $user->getCommentPopularity()) { ?>
					<span id="popularity">(Popularity: <?php echo $popularity;?>% over <?php echo ($user->getLikes() + $user->getDislikes());?> ratings)</span>
				<?php } ?>
				<ul id="commentList">
					<?php foreach ($comments as $comment) { ?>
						<li>
							<a href="<?php echo $comment->getURL(); ?>"><?php echo $comment->getArticle()->getTitle(); ?></a> <p><?php echo Utility::trimText($comment->getContent(), 130, false); ?></p>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<?php 
			$theme->render('sidebar/mostPopular');
		?>
	</div>
</div>
<?php $timing->log('end of user page');?>
<?php $theme->render('components/footer'); ?>
