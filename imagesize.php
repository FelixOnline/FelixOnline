<?php
    require_once('inc/common.inc.php');
    $sql = "SELECT * FROM `image`";
    $results = $db->get_results($sql);
    foreach($results as $image) {
        $url = get_img_url($image->id);
        echo $image->id.' '.$url."\n";
        $size = getimagesize($url);
        $insert = "UPDATE `image` SET width = ".$size[0].", height = ".$size[1]." WHERE id = ".$image->id;
        $db->query($insert);
    }
?>
