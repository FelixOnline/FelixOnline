		<?php
			restore_error_handler();
			restore_exception_handler();

			use \FelixOnline\Exceptions;
			use \FelixOnline\Core\CurrentUser;
		?>
		
		<div class="error_text">
			<h1>He's Dead, Jim!</h1>
			<p>Felix Online is experiencing technical difficulties at the moment. The cat has already been notified, and things should be back up and running soon.</p>
			<p>In the meantime, please enjoy this video:</p>
			<iframe width="480" height="360" src="https://www.youtube.com/embed/QgkGogPLacA?rel=0" frameborder="0" allowfullscreen></iframe>
			<?php if(LOCAL || ($e->getUser() instanceof CurrentUser && $e->getUser()->getRole() == 100)) { ?>
			<p id="techdetails_show" style="display: none;"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'block'; document.getElementById('techdetails_show').style.display = 'none';">View some technical details</a></p>
			<div id="techdetails" class="technical_details" style="display: block;">
				<p id="techdetails_hide"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'none'; document.getElementById('techdetails_show').style.display = 'block';">Hide the technical details</a></p>
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

						switch($exception->getCode()) {
							case Exceptions\UniversalException::EXCEPTION_ERRORHANDLER:
								$header = 'Internal error';
								$data['Details'] = $exception->getMessage();
								$data['File'] = $exception->getErrorFile();
								$data['Line'] = $exception->getErrorLine();
								break;
							case Exceptions\UniversalException::EXCEPTION_GLUE:
								$header = 'Misconfigured glue';
								$data['URL'] = $exception->getUrl();
								$data['Class requested'] = $exception->getClass();
								$data['Method requested'] = $exception->getMethod();
								break;
							case Exceptions\UniversalException::EXCEPTION_GLUE_URL:
								$header = 'URL is not valid';
								$data['URL'] = $exception->getUrl();
								break;
							case Exceptions\UniversalException::EXCEPTION_IMAGE_NOTFOUND:
								$dimensions = $exception->getImageDimensions();
								$header = 'Image could not be found';
								$data['Containing page'] = $exception->getPage();
								$data['Image URL'] = $exception->getImageUrl();
								$data['Requested dimensions'] = $dimensions['width'].'x'.$dimensions['height'];
								break;
							case Exceptions\UniversalException::EXCEPTION_MODEL:
								$header = 'Misconfigured model';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = $exception->getItem();
								$data['Action'] = $exception->getVerb();
								$data['Property'] = $exception->getProperty();
								break;
							case Exceptions\UniversalException::EXCEPTION_MODEL_NOTFOUND:
								$header = 'Item is not in database';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = json_encode($exception->getItem());
								break;
							case Exceptions\UniversalException::EXCEPTION_VIEW_NOTFOUND:
								$header = 'Template does not exist';
								$data['Template'] = $exception->getView();
								break;
							case Exceptions\UniversalException::EXCEPTION_NOTFOUND:
								$header = $exception->getMessage();
								$data['Matches'] = json_encode($exception->getMatches());
								$data['Controller'] = $exception->getController();
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
					echo '<h3>Backtrace</h3>';
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
						
						if(method_exists($exception, 'getUser') && $exception->getUser() instanceof CurrentUser && $exception->getUser()->pk != null) {
							$username = $exception->getUser()->getName().' ('.$exception->getUser()->getUser().')';
						} else {
							$username = 'Unauthenticated';
						}
						switch($exception->getCode()) {
							case Exceptions\UniversalException::EXCEPTION_ERRORHANDLER:
								$header = 'Internal error';
								$data['Details'] = $exception->getMessage();
								$data['File'] = $exception->getErrorFile();
								$data['Line'] = $exception->getErrorLine();
								break;
							case Exceptions\UniversalException::EXCEPTION_GLUE:
								$header = 'Misconfigured glue';
								$data['URL'] = $exception->getUrl();
								$data['Class requested'] = $exception->getClass();
								$data['Method requested'] = $exception->getMethod();
								break;
							case Exceptions\UniversalException::EXCEPTION_GLUE_URL:
								$header = 'URL is not valid';
								$data['URL'] = $exception->getUrl();
								break;
							case Exceptions\UniversalException::EXCEPTION_IMAGE_NOTFOUND:
								$dimensions = $exception->getImageDimensions();
								$header = 'Image could not be found';
								$data['Containing page'] = $exception->getPage();
								$data['Image URL'] = $exception->getImageUrl();
								$data['Requested dimensions'] = $dimensions['width'].'x'.$dimensions['height'];
								break;
							case Exceptions\UniversalException::EXCEPTION_MODEL:
								$header = 'Misconfigured model';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = $exception->getItem();
								$data['Action'] = $exception->getVerb();
								$data['Property'] = $exception->getProperty();
								break;
							case Exceptions\UniversalException::EXCEPTION_MODEL_NOTFOUND:
								$header = 'Item is not in database';
								$data['Item type'] = $exception->getClass();
								$data['Item identifier'] = json_encode($exception->getItem());
								break;
							case Exceptions\UniversalException::EXCEPTION_VIEW_NOTFOUND:
								$header = 'Template does not exist';
								$data['Template'] = $exception->getView();
								break;
							case Exceptions\UniversalException::EXCEPTION_NOTFOUND:
								$header = $exception->getMessage();
								$data['Matches'] = json_encode($exception->getMatches());
								$data['Controller'] = $exception->getController();
								break;
							default:
								$header = 'Internal exception';
								$data['Details'] = $exception->getMessage();
								$data['File'] = $exception->getFile();
								$data['Line'] = $exception->getLine();
								break;
						}

						$data['Exception file'] = $exception->getFile();
						$data['Exception line'] = $exception->getLine();

						$message .= $header."\n";
						$message .= "URL accessed: http://".STANDARD_SERVER.$_SERVER["REQUEST_URI"]."\n";
						$message .= "Method: ".$_SERVER['REQUEST_METHOD']."\n";
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
					$status = true;

					foreach($to as $addressee) {
						$successful = mail($addressee, $subject, $message);
						if(!$successful) {
							$status = false;
						}
					}
					
					if(!$status) {
						echo '<p><b>We couldn\'t notify the Felix Online developers, please contact us at felix@imperial.ac.uk for further assistance.</b></p>';
					} else {
						file_put_contents(dirname(__FILE__).'/../emails/fatal_next_notify', time() + 900);
					}
				}
			}
			?>
		</div>
