<?php

// Image processing script
function invalid($message) {
	header('HTTP/1.1 400 Bad Request');
	header("Cache-Control: no-cache, must-revalidate", false);
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

	echo $message;
	exit;
}

function error($message) {
	header('HTTP/1.1 500 Internal Server Error');
	header("Cache-Control: no-cache, must-revalidate", false);
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

	echo $message;
	exit;
}

function notfound($message) {
	header('HTTP/1.1 404 Not Found');
	header("Cache-Control: no-cache, must-revalidate", false);
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

	echo $message;
	exit;
}

$filename = $_GET['src'];

// Security measures
if(!$filename) {
	invalid('Invalid file name specified');
}

$filename = realpath($filename);

if(strpos($filename, __DIR__) !== 0) {
	invalid('File not in permitted directory');
}

if(!file_exists($filename)) {
	notfound('Image could not be found');
}

if(isset($_GET['w'])) {
	$w = $_GET['w'];
} else {
	$w = false;
}

if(isset($_GET['h'])) {
	$h = $_GET['h'];
} else {
	$h = false;
}

if(($w && preg_match('/[^0-9]/', $w)) || ($h && preg_match('/[^0-9]/', $h))) {
	invalid('Invalid dimensions specified');
}

// Combi URL

$combiurl = $filename;

if($w) {
	$combiurl .= $w;
}

if($h) {
	$combiurl .= $h;
}

$combiurl = sha1($combiurl);

// Check if ETAG matches
if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == $combiurl) {
	header("HTTP/1.1 304 Not Modified", TRUE, 304);
	exit();
}

try {
	$imageObj = new Imagick($filename);

	// We have two jobs. The first of these is to convert the colourspace if appropriate. Then, change the resolution
	$colorspace = $imageObj->getImageColorspace();

	if($colorspace != Imagick::COLORSPACE_RGB && $colorspace != Imagick::COLORSPACE_SRGB) { // Some images are uploaded CMYK  -these need conversion
		$ret = $imageObj->transformImageColorspace(Imagick::COLORSPACE_RGB);

		if(!$ret) {
			throw new Exception('Could not convert colourspace');
		}

		// Save for future
		$ret = $imageObj->writeImage();

		if(!$ret) {
			throw new Exception('Could not save colourspace');
		}
	}

	// Now to resize if needed
	if(!$h && $w) {
		$origWidth = $imageObj->getImageWidth();

		$fract = ($w / $origWidth); // Fraction we are changing the width by

		$h = $imageObj->getImageHeight() * $fract;
	}

	if($w && $h) {
		$ret = $imageObj->adaptiveResizeImage($w, $h);

		if(!$ret) {
			throw new Exception('Could not resize image');
		}
	}
	
	$image = $imageObj->getImageBlob();

	// Now render image
	header('HTTP/1.1 200 OK');
	header("Cache-Control: max-age=8640000, public", false);
	header("Expires: " . gmdate('D, d M Y H:i:s \G\M\T', time() + 8640000), false);
	header("ETag: \"".$combiurl."\"", false);
	header("Content-Length: ".strlen($image), false);
	header("Content-Type: ".$imageObj->getImageMimeType(), false);

	echo $image;

	exit;
} catch(Exception $e) {
	error($e->getMessage());
}