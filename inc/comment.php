<?php
    /*
     * Comments
     *
     * Handles submission of comments
     */
    global $cid;
    $extfailflag = false; // fail flag
    $article = $_GET['article']; // get current article

    $errorinsert = false; // error on insert flag
    $errorduplicate = false; // error on duplicate flag
    $errorrecapatcha = false; // error on recapatcha fail

    /* User comment */
    if ($_POST['articlecomment']) {
        $comment = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['comment'])); // get comment
        $replyName = mysql_real_escape_string($_POST['replyName']); // get name of comment that the comment is replying to
        $replyComment = mysql_real_escape_string($_POST['replyComment']); // get comment id of comment that the comment is replying to
        $uname = is_logged_in(); // get current user
        if (!comment_exists($article,$uname,$comment)) { // if comment doesn't exist
            if ($id = insert_comment($article,$uname,$comment,$replyName,$replyComment)) { // insert comment into database
                header('Location: '.full_article_url($article).'#comment'.$id); // redirect user to article page with comment anchor tag
            } else { // if insertion fails (lol)
                $errorinsert = true;
            }
        } else { // if comment exists
            $errorduplicate = true;
        }
    }

    /* External comment */
    else if ($_POST['articlecomment_ext']) {
        $comment = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['comment'])); // get comment
        $name = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['name'])); // get name of commenter
        //$email = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['email'])); // get email of commenter TODO
        //$url = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['url'])); // get url of commenter [depreciated] 
        $replyName = mysql_real_escape_string($_POST['replyName']); // get name of comment that the comment is replying to        
        $replyComment = mysql_real_escape_string($_POST['replyComment']); // get comment id of comment that the comment is replying to

        // ReCapatcha
        //A. Load the Recaptcha Libary
        require_once('recaptchalib.php');
         
        //B. Recaptcha Looks for the POST to confirm 
        $resp = recaptcha_check_answer(RECAPTCHA_PRIVATE_KEY,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
         
        //C. If if the User's authentication is valid, echo "success" to the Ajax
        if ($resp->is_valid) {
            // check spam using akismet
            require_once('akismet.class.php');

            $url = 'http://felixonline.co.uk';

            $akismet = new Akismet($url ,AKISMET_API_KEY);
            $akismet->setCommentAuthor($name);
            //$akismet->setCommentAuthorEmail($email);
            //$akismet->setCommentAuthorURL($url);
            $akismet->setCommentContent($comment);
            $akismet->setPermalink(full_article_url($article));

            if($akismet->isCommentSpam()) { // if comment is spam
                // store the comment but mark it as spam (in case of a mis-diagnosis)
                if ($id = insert_comment_spam($article,$name,$comment,$replyName,$replyComment)) {
                    // redirect to comment form
                    header('Location: '.full_article_url($article).'#commentForm');
                } else {
                    $errorinsert = true;
                }
            } else { // else
                // store the comment normally
                if($id = insert_comment_external($article,$name,$comment,$replyName,$replyComment)) {
                    // redirect to comment
                    header('Location: '.full_article_url($article).'#comment'.$id);
                }
            }
        } else {
            $errorrecapatcha = true; 
        }
    }
?>
