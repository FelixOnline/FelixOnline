<?php
    /*
     * Comments
     *
     * Handles submission of comments
     */
    $article = $_GET['article']; // get current article

    $errorinsert = false; // error on insert flag
    $errorduplicate = false; // error on duplicate flag
    $errorrecapatcha = false; // error on recapatcha fail
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
            if ($newComment->commentExists()) { // if comment already exists
                $errorduplicate = true;
            } else {
                if($id = $newComment->insert()) {
                    if($id == 'spam') {
                        $errorspam = true;
                    } else {
                        header('Location: '.full_article_url($article).'#comment'.$id);
                    }
                } else {
                    $errorinsert = true;
                }
            }
        } else {
            $errorrecapatcha = true; 
        }
    }
?>
