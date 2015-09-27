<?php
	$categoryManager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Category', 'category');

	if(!$currentuser->isLoggedIn()) {
		$categoryManager = $categoryManager->filter('secret = 0');
	}

	$articleManager = (new \FelixOnline\Core\ArticleManager())
		->filter('published < NOW()')
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

        <div class="latest-comments info-box" data-equalizer-watch="opinion">
          <h1>Latest comments</h1>
          <ul class="recent-comments">
<?php
	foreach($comments as $comment):
		$content = $comment->getComment();

		if(strlen($content) > 70) {
			$content = substr($content, 0, 70).'...';
		}
?>
            <li><?php echo $content; ?><br><span class="comment-meta">Posted by <span class="comment-name"><?php echo $comment->getName(); ?></span> on <span class="comment-article"><a href="<?php echo $comment->getArticle()->getUrl(); ?>"><?php echo $comment->getArticle()->getTitle(); ?></a></span>.</span></li>
<?php
	endforeach;
?>
          </ul>
        </div>