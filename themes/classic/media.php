<?php
$timing->log('media page');

$header = array(
    'title' => 'Felix Online Media'
);

$theme->render('header', $header);
?>
<div class="container_12 media">
</div>
<?php $timing->log('end of media page');?>
<?php $theme->render('footer'); ?>
