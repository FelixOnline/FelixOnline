<?php

use FelixOnline\Exceptions;
use FelixOnline\Core\CurrentUser;

$header = array(
	'title' => 'Felix Online - The student voice of Imperial College London',
	'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('components/header', $header); 

?>
	<!-- Article wrapper -->
	<div class="row felix-pad-top">
		<div class="small-12 columns">
			<?php $theme->render('components/advert', array('sidebar' => false)); ?>
			<h1>Sorry, couldn't find that</h1>
			<p>We are sorry, we couldn't find what you were looking for.</p>
			<ul>
				<li>If you have clicked a link on Felix Online, <a href="<?php echo STANDARD_URL; ?>contact">please let us know</a>.</li>
				<li>If not, try searching for the content you are looking for.</li>
				<li>Alternatively, return to the <a href="<?php echo STANDARD_URL; ?>">front page</a>.</li>
			</ul>

			<?php if(LOCAL) { ?>
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

						if(method_exists($exception, 'getUser') && $exception->getUser() instanceof CurrentUser && $exception->getUser()->pk != null) {
							$username = $exception->getUser()->getName().' ('.$exception->getUser()->getUser().')';
						} else {
							$username = '<i>Unknown</i>';
						}

						switch ($exception->getCode()) {
							case FrontendException::EXCEPTION_NOTFOUND:
								$header = $exception->getMessage();
								$data['URL'] = $exception->getURL();
								$data['Matches'] = json_encode($exception->getMatches());
								$data['Controller'] = $exception->getController();
								$data['File'] = $exception->getFile();
								$data['Line'] = $exception->getLine();
								break;
							default:
								$header = 'Unknown Error';
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
