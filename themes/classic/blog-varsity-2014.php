<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Varsity 2014">
	<meta name="author" content="">
	<link rel="shortcut icon" href="/favicon.ico">

	<title>Varsity 2014 - FelixOnline</title>

	<!-- CSS -->
	<?php
		$theme->resources->replaceCSS(array(
			'lib/bootstrap-3.1.1.css',
			'liveblog.less',
		));
	?>
	<?php foreach($theme->resources->getCSS() as $key => $value) { ?>
		<link id="<?php echo $key;?>" rel="stylesheet" href="<?php echo $value; ?>">
	<?php } ?>
</head>
<body>
	<div class="container">
		<div class="header">
			<div class="logo">
				<h1><a href="<?php echo STANDARD_URL; ?>">FELIX</a></h1>
			</div>
		</div>
		<div class="masthead">
		</div>

		<div class="row">
			<div class="col-md-6 col-md-offset-1 info">
				<h3>Live Reporting</h3>
				<div class="reporters">
					By <a href="<?php echo STANDARD_URL . 'user/kmw13'; ?>">Kunal Wagle</a>
				</div>
				<div class="status">
					<div id="disconnected">
						Disconnected.
					</div>
					<div id="connected" style="display: none;">
						Connected. Page will update automatically.
					</div>
				</div>
			</div>
		</div>
		<div class="content row">
			<div class="col-md-6 col-md-offset-1 feed">
				<?php
					// Output posts
					foreach ($posts as $post) {
						$json = $post->toJSON();
						$json['time'] = date('G:i', $json['timestamp']);

						// load template
						$template = $mustache->loadTemplate($json['type']);
						echo $template->render($json);
					}
				?>
			</div>
		</div>
	</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//cdn.sockjs.org/sockjs-0.3.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/hogan.js/3.0.0/hogan.js"></script>
	<script src="//platform.twitter.com/widgets.js"></script>
	<?php
		$theme->resources->replaceJS(array(
			'liveblog-templates.js',
			'liveblog.js',
		));
	?>
	<?php foreach($theme->resources->getJS() as $key => $value) { ?>
		<script src="<?php echo $value; ?>"></script>
	<?php } ?>

	<script type="text/javascript">
		$(function() {
			LiveBlog.init('http://176.34.227.200:3000/varsity-2014', $('.feed'));
		});
	</script>

	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-12220150-1']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</body>
</html>
