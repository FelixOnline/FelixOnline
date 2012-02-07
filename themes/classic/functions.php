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
}

?>
