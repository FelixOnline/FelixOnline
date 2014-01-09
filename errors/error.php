		<?php
			require_once('inc/exceptions.inc.php');
			restore_error_handler();
			restore_exception_handler();
		?>
		
		<div class="error_text">
			<h1>He's Dead, Jim!</h1>
			<p>Felix Online is experiencing technical difficulties at the moment. The cat has already been notified, and things should be back up and running soon.</p>
			<p>In the meantime, please enjoy this video:</p>
			<iframe width="480" height="360" src="http://www.youtube.com/embed/QgkGogPLacA?rel=0" frameborder="0" allowfullscreen></iframe>
			<p id="techdetails_show" <?php if(LOCAL): echo 'style="display: none;"'; else: echo 'style="display: block;"'; endif; ?>><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'block'; document.getElementById('techdetails_show').style.display = 'none';">View some technical details</a></p>
			<div id="techdetails" class="technical_details" <?php if(LOCAL): echo 'style="display: block;"'; else: echo 'style="display: none;"'; endif; ?>>
				<p id="techdetails_hide"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'none'; document.getElementById('techdetails_show').style.display = 'block';">Hide the technical details</a></p>
				<?php
					$exceptions = array($prior_exception, $e);
					
					foreach($exceptions as $exception) {
						if($exception == null) {
							continue;
						}
						
						$data = array();

						if(!($exception instanceof Exception) && $exception->getUser() instanceof CurrentUser && $exception->getUser()->getUser() instanceof User) {
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
								$data['URL'] = $exception->getUrl();
								$data['Class requested'] = $exception->getItem();
								$data['Method requested'] = $exception->getVerb();
								break;
							case EXCEPTION_GLUE_URL:
								$header = 'URL is not valid';
								$data['URL'] = $exception->getUrl();
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
					$to = explode(',', EMAIL_ERRORS);
					$subject = 'Felix Online error';
					$message = "An error has occured on Felix Online\n";
					$message .= "Details on the error is below. If there are multiple errors, the first is the main one, the second prevented the usual error page from being shown\n\n";
					
					$exceptions = array($prior_exception, $e);

					foreach($exceptions as $exception) {
						if ($exception) {
							$data = array();
							$data['Details'] = $exception->getMessage();
							
							if(!($exception instanceof Exception) && $exception->getUser()->getUser() instanceof User) {
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
					}

					//$message = wordwrap($message, 70);
					if($notify) {
						$status = false;

						foreach($to as $addressee) {
							$successful = mail($addressee, $subject, $message);

							if($successful) {
								$status = true;
							}
						}
						
						if(!$status) {
							echo 'Notification failed';
						} else {
							file_put_contents(dirname(__FILE__).'/../emails/fatal_next_notify', time() + 900);
						}
					}
				}
				?>
			</div>
