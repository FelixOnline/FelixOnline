<?php
$timing->log('category page');

$header = array(
    'title' => $category->getLabel().' - '.'Felix Online',
    'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header);
?>

<?php
$timing->log('end of category page');
$theme->render('footer');
?>
