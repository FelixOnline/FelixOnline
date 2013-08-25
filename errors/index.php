<?php try { ?>
<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 7 ]>	<html lang="en" class="no-js ie7" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 8 ]>	<html lang="en" class="no-js ie8" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 9 ]>	<html lang="en" class="no-js ie9" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js" xmlns:fb="http://ogp.me/ns/fb#"> <!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# feliximperial: http://ogp.me/ns/fb/feliximperial#">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="keywords" content="felix, student news, student newspaper, felix online, imperial college union, imperial college, felixonline"/>
	<meta name="description" content="Felix Online is the online companion to Felix, the student newspaper of Imperial College London.">
	<meta name="author" content="Jonathan Kim">
	<meta name="google-site-verification" content="V5LPwqv0BzMHvfMOIZvSjjJ-8tJc4Mi1A-L2AEbby50" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<base href="<?php echo STANDARD_URL; ?>">

	<!-- Title -->
	<title>
	   	Felix Online - The student voice of Imperial College London 
	</title>

	<!-- Facebook -->
	<meta property="og:site_name" content="Felix Online"/>
	<meta property="fb:app_id" content="200482590030408" />

	<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
	<link rel="shortcut icon" href="favicon.ico">
	<!-- CSS files -->
	<link rel="stylesheet" href="<?php echo STANDARD_URL; ?>errors/styles.css">
</head>
<body>
	<div class="header">
		<img class="felix" alt="FELIX" src="<?php echo STANDARD_URL; ?>errors/felix.jpg" />
	</div>
	<div class="box">
		<img class="error_cat" alt="" src="<?php echo STANDARD_URL; ?>errors/cat.jpg" />
		<?php require('error.php'); ?>
		&copy; Felix Imperial
	</div>
</body>
</html>
<?php } catch (Exception $e) {} ?>
