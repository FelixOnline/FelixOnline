<?php

	require_once('../inc/common.inc.php');
	
	header('Content-type: image/jpeg');
	if (!($_GET['nocache']))
		header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600*IMG_EXPIRY_HOURS) . " GMT");
	
	/* if $_GET['article'] (article ID) is set, $_GET['img'] is 1 or 2 - which image for that article */
	/* else, $_GET['img'] (image ID) is the ID of the image */
	
	$width_d = $_GET['width'];
	if (isset($_GET['article']) && is_numeric($article = $_GET['article'])) {
		if (!($img = $_GET['img']))
			$img = 1;
	}
	if (isset($img)) {
		$img_id = get_img_id($article,$img);
		$filename = '../'.get_article_img_uri($article,$img);
	}
	else {
		$img_id = $_GET['img'];
		$filename = '../'.get_img_uri($img_id);
	}
	
	list($width_s,$height_s) = getimagesize($filename);
	$height_s_orig = $height_s; // check requested height < full original height before cropping
	if (!($height_d = ($height_req = $_GET['height'])))
		$height_d = $height_s * ($width_d/$width_s);
	else
		$height_s = $height_d * ($width_s/$width_d);
	if ($height_req && (($height_diff = $height_s_orig-$height_s) > 0)) {
		$v_offset = get_img_v_offset($img_id);
		if ($v_offset > $height_diff)
			$v_offset = $height_diff; // don't over-crop
	}
	else
		$v_offset = 0;
	$aspect_s = $width_s/($height_s_orig-$v_offset);
	$aspect_d = $width_d/$height_d;
	if (($aspectratiosratio = ($aspect_d/$aspect_s)) < 1) { // aspect ratios ratio?! uhm... yeah
		$width_s *= $aspectratiosratio;
		$height_s *= $aspectratiosratio;
		$h_offset = get_img_h_offset($img_id);
	}
	else
		$h_offset = 0;
	$img_s = imagecreatefromjpeg($filename);
	$img_d = imagecreatetruecolor($width_d,$height_d);
	
	imagecopyresampled($img_d,$img_s,0,0,$h_offset,$v_offset,$width_d,$height_d,$width_s,$height_s);
	
	return imagejpeg($img_d,NULL,IMG_QUALITY_PERCENT);
	
?>