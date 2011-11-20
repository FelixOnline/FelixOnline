<?php
    /*
     * Comments
     *
     * Handles submission of comments
     */
    global $cid;
    $extfailflag = false; // fail flag
    $article = $_GET['article']; // get current article

    // ReCapatcha
    // Store in const.inc.php?? TODO
    $publickey = "6LdbYL4SAAAAAKufkLBCRiEmbTRawSFaWDDJwQwB";
    $privatekey = "6LdbYL4SAAAAAOAUmQ4QSXUbSYm1LIkgbvqZBWXU";

    $errorinsert = false; // error on insert flag
    $errorduplicate = false; // error on duplicate flag

    // user comment
    if ($_POST['articlecomment']) {
        $comment = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['comment'])); // get comment
        $replyName = mysql_real_escape_string($_POST['replyName']); // get name of comment that the comment is replying to
        $replyComment = mysql_real_escape_string($_POST['replyComment']); // get comment id of comment that the comment is replying to
        $uname = is_logged_in(); // get current user

        if (!check_comment_exists($article,$uname,$comment)) { // if comment doesn't exist
            if ($id = insert_comment($article,$uname,$comment,$replyName,$replyComment)) { // insert comment into database
                // redirect to comment
                header('Location: '.full_article_url($article).'#comment'.$id); // redirect user to article page with comment anchor tag
            } else { // if insertion fails (lol)
                $errorinsert = true;
            }
        } else // if comment exists
            $errorduplicate = true;
    }

    // external comment
    else if ($_POST['articlecomment_ext']) {
        $comment = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['comment'])); // get comment
        $name = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['name'])); // get name of commenter
        $email = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['email'])); // get email of commenter TODO
        //$url = mysql_real_escape_string(get_correct_utf8_mysql_string($_POST['url'])); // get url of commenter [depreciated] 
        $replyName = mysql_real_escape_string($_POST['replyName']); // get name of comment that the comment is replying to        
        $replyComment = mysql_real_escape_string($_POST['replyComment']); // get comment id of comment that the comment is replying to

        // check spam using akismet
        require_once('akismet.class.php');

        $apikey = '4c2ddc0022f0'; // akismet api key [TODO: move to const.inc.php]
        $url = 'http://felixonline.co.uk';

        $akismet = new Akismet($url ,$apikey);
        $akismet->setCommentAuthor($name);
        $akismet->setCommentAuthorEmail($email);
        $akismet->setCommentAuthorURL($url);
        $akismet->setCommentContent($comment);
        $akismet->setPermalink(full_article_url($article));

        if($akismet->isCommentSpam()) { // if comment is spam
            // store the comment but mark it as spam (in case of a mis-diagnosis)
            if ($id = insert_comment_spam($article,$name,$comment,$replyName,$replyComment)) {
                // redirect to comment
                header('Location: '.full_article_url($article).'#comment'.$id);
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
    }
?>
