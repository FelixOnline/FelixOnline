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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Title -->
	<title>
	   	Felix Online - The student voice of Imperial College London 
	</title>

	<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
	<link rel="shortcut icon" href="favicon.ico">
	<!-- CSS files -->
	<link rel="stylesheet" href="errors/styles.css">
</head>
<body>
	<div class="header">
		<h1>Felix Online</h1>
	</div>
	<div class="box">
		<div class="error_text">
			<h1>This website is offline</h1>
			<?php
				if(file_get_contents('.maintenance') == "") {
					echo '<p>We are sorry, this website is offline for maintenance.</p>';
				} else {
					echo file_get_contents('.maintenance');
				}
			?>
		</div>
		&copy; Felix Imperial
	</div>
</body>
</html>
<?php } catch (Exception $e) {} ?>
