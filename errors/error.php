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
			?>
		</div>
