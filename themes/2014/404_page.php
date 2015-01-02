<?php

use FelixOnline\Exceptions;

$timing->log('404 error');

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header); 
$timing->log('after header');

?>
	<!-- Article wrapper -->
	<div class="row felix-pad-top">
		<div class="small-12 columns">
			<h1>Sorry, couldn't find that</h1>
			<p>We are sorry, we couldn't find what you were looking for.</p>
			<ul>
				<li>If you have clicked a link on Felix Online, <a href="<?php echo STANDARD_URL; ?>contact">please let us know</a>.</li>
				<li>If not, try searching for the content you are looking for.</li>
				<li>Alternatively, return to the <a href="<?php echo STANDARD_URL; ?>">front page</a>.</li>
			</ul>

			<?php if(LOCAL || ($e->getUser() instanceof CurrentUser && $e->getUser()->getRole() == 100)) { ?>
			<p id="techdetails_show" style="display: none;"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'block'; document.getElementById('techdetails_show').style.display = 'none';">View some technical details</a></p>
			<div id="techdetails" class="technical_details" style="display: block;">
				<p id="techdetails_hide"><a href="javascript:void();" onClick="document.getElementById('techdetails').style.display = 'none'; document.getElementById('techdetails_show').style.display = 'block';">Hide the technical details</a></p>
				<?php
					$exceptions = array($prior_exception, $e);
					
					foreach($exceptions as $exception) {
						if($exception == null) {
							continue;
						}

						$data = array();

						if(method_exists($exception, 'getUser') && $exception->getUser() instanceof CurrentUser) {
							$username = $exception->getUser()->getName().' ('.$exception->getUser()->getUser().')';
						} else {
							$username = '<i>Unauthenticated</i>';
						}

						switch ($exception->getCode()) {
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
			?>
			</div>
		</div>
	</div>
	<!-- End of article wrapper -->

<?php $theme->render('components/footer'); ?>
