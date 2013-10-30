<?php
    /*
     * Comments
     *
     * Handles submission of comments
     */
    $article = $_GET['article']; // get current article

    $errorinsert = false; // error on insert flag
    $errorduplicate = false; // error on duplicate flag
    $errorakismet = false; // error on recapatcha fail
    $errorspam = false; // error on spam catch

    $newComment = new Comment();
    $newComment->setArticle($article);

    /* User comment */
    if ($_POST['articlecomment']) {
        $newComment->setExternal(false);
        $newComment->setContent($_POST['comment']);
        $newComment->setUser(is_logged_in());
        if(isset($_POST['replyComment'])) {
            $newComment->setReply($_POST['replyComment']);
        }
        if ($newComment->commentExists()) { // if comment already exists
            $errorduplicate = true;
        } else {
            if($id = $newComment->insert()) { 
                header('Location: '.full_article_url($article).'#comment'.$id); // redirect user to article page with comment anchor tag
            } else {
                $errorinsert = true;
            }
        }
    }

    /* External comment */
    else if ($_POST['articlecomment_ext']) {
        $newComment->setExternal(true);
        $newComment->setContent($_POST['comment']);
        $newComment->setName($_POST['name']);
        if(isset($_POST['replyComment'])) {
            $newComment->setReply($_POST['replyComment']);
        }

        // Akismet
        //A. Load the Akismet Libary

        require_once('Akismet/Akismet.php');
        require_once('Akismet/Connector/ConnectorInterface.php');
        require_once('Akismet/Connector/Curl.php');
        require_once('Akismet/Connector/PHP.php');

        try {
        	$ak = new RzekaE\Akismet\Akismet(AKISMET_API_KEY, BASE_URL);
        } catch(Exception $e) {
        	$errorakismet = true;
        }

        if(!$errorakismet) {
		//B. Build comment array for akismet
		$comment = array(
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'user_agent' => $_SERVER['HTTP_USER_AGENT'],
			'referrer' => $_SERVER['HTTP_REFERER'],
			'comment_type' => 'comment',
			'comment_author' => $_POST['name'],
			'comment_content' => $_POST['comment'],
			'permalink' => full_article_url($article));

		//C. Call Akismet
		try {
			$status = $ak->check($comment);
		} catch(Exception $e) {
			$errorakismet = true;
         	}

	}

	if(!$errorakismet) {
        	//D. Parse response
        	    if ($newComment->commentExists()) { // if comment already exists
        	        $errorduplicate = true;
        	    } else {
        	    if($status) { $status = 1; } else { $status = 0; }
        	        if($id = $newComment->insert($status)) {
        	            if($status == 1) {
        	                $errorspam = true;
        	            } else {
        	                header('Location: '.full_article_url($article).'#comment'.$id);
        	            }
        	        } else {
        	            $errorinsert = true;
        	        }
        	    }
        }
    }
?>
