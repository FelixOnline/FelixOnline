<?php try { ?>
<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9" xmlns:fb="http://ogp.me/ns/fb#"> <![endif]-->
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
		<img class="cat" alt="" src="<?php echo STANDARD_URL; ?>errors/cat.jpg" />
		<div class="text">
			<h1>He's Dead, Jim!</h1>
			<p>Felix Online is experiencing major technical difficulties at the moment. The cat has already been notified, and things should be back up and running soon.</p>
			<p>In the meantime, please enjoy this video:</p>
			<iframe width="480" height="360" src="http://www.youtube.com/embed/QgkGogPLacA?rel=0" frameborder="0" allowfullscreen></iframe>
			<p id="techdetails_show" <?php if(LOCAL): echo 'style="display: none;"'; else: echo 'style="display: block;"'; endif; ?>><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'block'; document.getElementById('techdetails_show').style.display = 'none';">View some technical details</a></p>
			<div id="techdetails" class="technical_details" <?php if(LOCAL): echo 'style="display: block;"'; else: echo 'style="display: none;"'; endif; ?>>
				<p id="techdetails_hide"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'none'; document.getElementById('techdetails_show').style.display = 'block';">Hide the technical details</a></p>
				<p>The first issue shown below is the main problem causing this message. The second is an additional problem which prevented showing the usual error page.</p>
				<?php
					$exceptions = array($prior_exception, $e);
					
					foreach($exceptions as $exception) {
						if($exception == null) {
							continue;
						}
						
						$data = array();
						if($exception->getUser() instanceof CurrentUser && $exception->getUser()->getUser() instanceof User) {
							$username = $exception->getUser()->getUser()->getName();
						} else {
							$username = '<i>Unauthenticated</i>';
						}
						switch ($exception->getCode()) {
							case EXCEPTION_ERRORHANDLER:
								$header = 'Internal error';
								$data['Details'] = $exception->getMessage();
								$data['File'] = $exception->getErrorFile();
								$data['Line'] = $exception->getErrorLine();
								break;
							case EXCEPTION_GLUE:
								$header = 'Misconfigured glue';
								$data['URL'] = $exception->getClass();
								$data['Class requested'] = $exception->getItem();
								$data['Method requested'] = $exception->getVerb();
								break;
							case EXCEPTION_GLUE_URL:
								$header = 'URL is not valid';
								$data['URL'] = $exception->getClass();
								break;
							case EXCEPTION_IMAGE_NOTFOUND:
								$dimensions = $exception->getImageDimensions();
								$header = 'Image could not be found';
								$data['Containing page'] = $exception->getPage();
								$data['Image URL'] = $exception->getImageUrl();
								$data['Requested dimensions'] = $dimensions['width'].'x'.$dimensions['height'];
								break;
							case EXCEPTION_MODEL:
								$header = 'Misconfigured model';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = $exception->getItem();
								$data['Action'] = $exception->getVerb();
								$data['Property'] = $exception->getProperty();
								break;
							case EXCEPTION_MODEL_NOTFOUND:
								$header = 'Item is not in database';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = $exception->getItem();
								break;
							case EXCEPTION_VIEW_NOTFOUND:
								$header = 'Template does not exist';
								$data['Template'] = $exception->getView();
								break;
							default:
								$header = 'Internal exception';
								$data['Details'] = $exception->getMessage();
								$data['File'] = $exception->getFile();
								$data['Line'] = $exception->getLine();
								break;
						}
					?>
					<h2><?php echo $header; ?></h2>
					<ul>
						<li><b>Username:</b> <?php echo $username; ?></li>
						<?php
							foreach($data as $name => $value) {
								echo '<li><b>'.$name.':</b> '.$value.'</li>';
							}
						?>
					</ul>
					<?php
					if(LOCAL) {
						echo '<h3>Backtrace <i>(shown in local mode only)</i></h3>';
						echo '<pre>'.$exception->getTraceAsString().'</pre>';
					}
				}

				if(!LOCAL) {
					$notify = true;
					if(file_exists(dirname(__FILE__).'/../emails/fatal_next_notify')) {
						$next_notify = (int) file_get_contents(dirname(__FILE__).'/../emails/fatal_next_notify');
						if($next_notify > time()) {
							// We have notified already, don't notify again
							$notify = false;
						}
					}
					$to = 'jkimbo@gmail.com, philip.kent@me.com';
					$subject = 'Felix Online fatal error';
					$message = "A fatal error has occured on Felix Online\n";
					$message .= "Details on the error is below. The first exception is the main one, the second is an exception which prevented the usual error page from being shown\n\n";
					
					$exceptions = array($prior_exception, $e);
					
					foreach($exceptions as $exception) {
						$data = array();
						$data['Details'] = $exception->getMessage();
						
						if($exception->getUser()->getUser() instanceof User) {
							$username = $exception->getUser()->getUser()->getName();
						} else {
							$username = 'Unauthenticated';
						}
						switch ($exception->getCode()) {
							case EXCEPTION_ERRORHANDLER:
								$header = 'Internal error';
								$data['File'] = $exception->getErrorFile();
								$data['Line'] = $exception->getErrorLine();
								break;
							case EXCEPTION_GLUE:
								$header = 'Misconfigured glue';
								$data['URL'] = $exception->getClass();
								$data['Class requested'] = $exception->getItem();
								$data['Method requested'] = $exception->getVerb();
								break;
							case EXCEPTION_GLUE_URL:
								$header = 'URL is not valid';
								$data['URL'] = $exception->getClass();
								break;
							case EXCEPTION_IMAGE_NOTFOUND:
								$dimensions = $exception->getImageDimensions();
								$header = 'Image could not be found';
								$data['Containing page'] = $exception->getPage();
								$data['Image URL'] = $exception->getImageUrl();
								$data['Requested dimensions'] = $dimensions['width'].'x'.$dimensions['height'];
								break;
							case EXCEPTION_MODEL:
								$header = 'Misconfigured model';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = $exception->getItem();
								$data['Action'] = $exception->getVerb();
								$data['Property'] = $exception->getProperty();
								break;
							case EXCEPTION_MODEL_NOTFOUND:
								$header = 'Item is not in database';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = $exception->getItem();
								break;
							case EXCEPTION_VIEW_NOTFOUND:
								$header = 'Template does not exist';
								$data['Template'] = $exception->getView();
								break;
							default:
								$header = 'Internal exception';
								break;
						}

						$data['Exception file'] = $exception->getFile();
						$data['Exception line'] = $exception->getLine();

						$message .= $header."\n";
						$message .= "Username: ".$username."\n";
						foreach($data as $name => $value) {
							$message .= $name.": ".$value."\n";
						}
						$message .= "\nBacktrace:\n";
						$message .= $exception->getTraceAsString();
						$message .= "\n\n\n";
					}
						
					//$message = wordwrap($message, 70);
					
					if($notify) {
						$status = mail($to, $subject, $message);
						
						if(!$status) {
							echo 'Notification failed';
						} else {
							file_put_contents(dirname(__FILE__).'/../emails/fatal_next_notify', time() + 900);
						}
					}
				}
				?>
			</div>
			&copy; Felix Imperial
		</div>
	</div>
</body>
</html>
<?php } catch (Exception $e) {} ?>
