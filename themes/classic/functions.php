<?php

$hooks->addAction('contact_us', 'contact_us');

function contact_us() {
    var_dump($_REQUEST);
    // Send email
}

?>
