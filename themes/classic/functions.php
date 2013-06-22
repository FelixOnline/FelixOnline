<?php

$hooks->addAction('contact_us', 'contact_us');
function contact_us() {
    $name = $_REQUEST['name'];
    $emailaddress = $_REQUEST['email'];
    $message = $_REQUEST['message'];

    $email = new Email(); 

    $email->setTo('felix@imperial.ac.uk');

    if($name)
        $email->setSubject($name.' sent a message');
    else
        $email->setSubject('Anonymous message');
    
    $email->setContent($message);
    
    if($emailaddress) {
        if($name) {
            $email->setFrom($emailaddress, $name);
        } else {
            $email->setFrom($emailaddress);
        }
        $email->setReplyTo($emailaddress);
    }
    
    // Mail it
    $email->send();
    
    return 'Success';
}

$hooks->addAction('like_comment', 'like_comment');
function like_comment() {
    global $currentuser;
    if($currentuser->isLoggedIn()) {
        $user = $currentuser->getUser();
        $comment = $_REQUEST['comment'];
        $comment = new Comment($comment);
        $count = $comment->likeComment($user);
        return $count;
    } else {
        return (array(error => true, details => 'Not logged in'));
    }
}

$hooks->addAction('dislike_comment', 'dislike_comment');
function dislike_comment() {
    global $currentuser;
    if($currentuser->isLoggedIn()) {
        $user = $currentuser->getUser();
        $comment = $_REQUEST['comment'];
        $comment = new Comment($comment);
        $count = $comment->dislikeComment($user);
        return $count;
    } else {
        return (array(error => true, details => 'Not logged in'));
    }
}

$hooks->addAction('profile_change', 'profile_change');
function profile_change() {
    global $currentuser;
    if($currentuser->isLoggedIn()) {
        $user = new User();
		// Validate input here
        $user->setUser($currentuser->getUser());
        $user->setDescription($_POST['desc']);
        $user->setEmail($_POST['email']);
        $user->setFacebook($_POST['facebook']);
        $user->setTwitter($_POST['twitter']);
        $user->setWebsitename($_POST['webname']);
        $user->setWebsiteurl(Utility::addhttp($_POST['weburl']));
        $user->save();
		return (array(error => false));
    } else {
        return (array(error => true, details => 'Not logged in'));
    }
}
?>
