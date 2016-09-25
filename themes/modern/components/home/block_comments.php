<?php
	$articleManager = (new \FelixOnline\Core\ArticleManager())
		->enablePublishedFilter();

	$emailManager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\EmailValidation', 'email_validation');
	$emailManager->filter('confirmed = 1');

	$comments = (new \FelixOnline\Core\CommentManager())
		->order('timestamp', 'DESC')
		->filter('active = 1')
		->filter('spam = 0')
		->join($articleManager, null, 'article')
		->join($emailManager, null, 'email', 'email')
		->limit(0,3)
		->values();
?>

        <div class="latest-comments info-box" data-equalizer-watch="opinion">
          <h1>Latest comments</h1>
          <ul class="recent-comments">
<?php
	if($comments):
		foreach($comments as $comment):
			try {
				$comment->getArticle(); // Secret categories
			} catch(\Exception $e) {
				continue;
			}

			if(!$comment->isAccessible()) {
				continue;
			}

			$content = $comment->getComment();

			if(strlen($content) > 70) {
				$content = substr($content, 0, 70).'...';
			}
?>
	            <li><?php echo $content; ?><br><span class="comment-meta">Posted by <span class="comment-name"><?php echo $comment->getName(); ?></span> on <span class="comment-article"><a href="<?php echo $comment->getArticle()->getUrl(); ?>"><?php echo $comment->getArticle()->getTitle(); ?></a></span>.</span></li>
<?php
		endforeach;
	endif;
?>
          </ul>
        </div>