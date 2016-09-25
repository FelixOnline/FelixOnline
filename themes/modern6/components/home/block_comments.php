<?php
	$categoryManager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Category', 'category');

	$articleManager = (new \FelixOnline\Core\ArticleManager())
		->enablePublishedFilter()
		->join($categoryManager, null, 'category');

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
		<div data-equalizer-watch="opinion">
        <div class="latest-comments info-box info-title-only">
          <h1>Your Views</h1>
        </div>
        <div class="info-secondary-box">
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
            <li><?php echo $content; ?><br><span class="comment-meta">By <span class="comment-name"><?php echo $comment->getName(); ?></span> on <span class="comment-article"><a href="<?php echo $comment->getArticle()->getUrl(); ?>"><?php echo $comment->getArticle()->getTitle(); ?></a></span>.</span></li>
<?php
		endforeach;
	endif;
?>
          </ul>
        </div>
        </div>
        </div>