<?php
	use \FelixOnline\Exceptions;
	use \FelixOnline\Core\CurrentUser;
	restore_error_handler();
	restore_exception_handler();
?>

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

	<base href="<?php echo STANDARD_URL; ?>">

	<!-- Title -->
	<title>
	   	Felix Online - The student voice of Imperial College London 
	</title>

	<!-- Place favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
	<link rel="shortcut icon" href="favicon.ico">
	<!-- CSS files -->
	<link rel="stylesheet" href="<?php echo STANDARD_URL; ?>errors/styles.css">
</head>
<body>
	<div class="header">
		<h1>Felix Online</h1>
	</div>
	<div class="box">
		<div class="error_text">
			<h1>We are sorry, an error has occured</h1>
			<p><small>Error #<?php echo $e->getCode(); ?></small></p>
			<p>Unfortunately we are experiencing difficulty in loading the page you have requested.</p>
			<p>We may only be having trouble with this page, and so you may be able to browse the rest of the website.</p>
			<p>The error has been reported to our technical team and will be fixed as soon as possible. We apologise for the inconvenience this causes.</p>
			<?php if(LOCAL || DEBUG_MODE) { ?>
			<p id="techdetails_show" style="display: none;"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'block'; document.getElementById('techdetails_show').style.display = 'none';">View some technical details</a></p>
			<div id="techdetails" class="technical_details" style="display: block;">
				<p id="techdetails_hide"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'none'; document.getElementById('techdetails_show').style.display = 'block';">Hide the technical details</a></p>
			<?php } ?>
				<?php
					if (!isset($prior_exception) || !$prior_exception) {
						$prior_exception = null;
					}

					$exceptions = array($prior_exception, $e);
					
					foreach($exceptions as $exception) {
						if ($exception == null) {
							continue;
						}
						
						$data = array();

						if(method_exists($exception, 'getUser') && $exception->getUser() instanceof CurrentUser && $exception->getUser()->pk != null) {
							$username = $exception->getUser()->getName().' ('.$exception->getUser()->getUser().')';
						} elseif($exception instanceof \Exception) {
							$username = '<i>Unknown</i>';
						}

						$data['Details'] = $exception->getMessage();

						switch($exception->getCode()) {
							case FrontendException::EXCEPTION_NOTFOUND:
								$header = $exception->getMessage();
								$data['URL'] = $exception->getURL();
								$data['Matches'] = json_encode($exception->getMatches());
								$data['Controller'] = $exception->getController();
								break;
							case FrontendException::EXCEPTION_GLUE:
								$header = 'Glue misconfigured';
								$data['URL'] = $exception->getURL();
								$data['Class'] = $exception->getClass();
								$data['Method'] = $exception->getMethod();
								break;
							case FrontendException::EXCEPTION_GLUE_METHOD:
								$header = 'Glue misconfigured - method not valid for class';
								$data['URL'] = $exception->getURL();
								$data['Class'] = $exception->getClass();
								$data['Method'] = $exception->getMethod();
								break;
							case FrontendException::EXCEPTION_GLUE_URL:
								$header = 'No match for URL in glue';
								$data['URL'] = $exception->getURL();
								break;
							case FrontendException::EXCEPTION_FRONTEND:
								$header = 'Frontend exception';
								$data['URL'] = $exception->getURL();
								break;
							case Exceptions\UniversalException::EXCEPTION_ERRORHANDLER:
								$header = 'Error handled';
								$data['Error number'] = $exception->getErrno();
								$data['File'] = $exception->getErrorFile();
								$data['Line'] = $exception->getErrorLine();
								$data['Context'] = $exception->getContext();
								break;
							case Exceptions\UniversalException::EXCEPTION_MODEL:
								$header = 'Misconfigured model';
								$data['Verb'] = $exception->getVerb();
								$data['Property'] = $exception->getProperty();
								$data['Class'] = $exception->getClass();
								$data['Item'] = $exception->getItem();
								break;
							case Exceptions\UniversalException::EXCEPTION_MODEL_NOTFOUND:
								$header = 'Model Not Found';
								$data['Table'] = $exception->getClass();
								$data['Primary Key'] = $exception->getItem();
								break;
							case Exceptions\UniversalException::EXCEPTION_SQL:
								$header = 'SQL Error';
								$data['Query'] = $exception->getQuery();
								break;
							case Exceptions\UniversalException::EXCEPTION_INTERNAL:
								$header = 'Internal Exception';
								break;
							default:
								$header = 'Unknown exception';
								break;
						}

						$data['File'] = $exception->getFile();
						$data['Line'] = $exception->getLine();
					?>
				<?php if(LOCAL || DEBUG_MODE) { ?>
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
					echo '<h3>Backtrace</h3>';
					echo '<pre>'.$exception->getTraceAsString().'</pre>';
				}

				unset($data['File']);
				unset($data['Line']);
				unset($data['URL']);

				$data['Username'] = strip_tags($username);

				$event_id = $app['sentry']->getIdent($app['sentry']->captureException($exception, array('extra' => $data)));
				echo '<p>Created event ID '.$event_id.'</p>';
			}

			?>
		</div>
		&copy; Felix Imperial
	</div>
</body>
</html>
<?php } catch (Exception $e) {} ?>
