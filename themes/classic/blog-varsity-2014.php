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
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=139621629514583";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	<div class="container">
		<div class="header">
			<div class="logo">
				<h1><a href="<?php echo STANDARD_URL; ?>">FELIX</a></h1>
			</div>
		</div>
		<div class="masthead">
			<img src="<?php echo STANDARD_URL . "themes/" . THEME_NAME . "/img/varsity-2014.jpg"; ?>"/>
		</div>

		<div class="row">
			<div class="col-md-12 info clearfix">
				<h3>Live Reporting</h3>
				<div class="info-left">
					<div class="reporters">
						By <a href="<?php echo STANDARD_URL . 'user/kmw13'; ?>">Kunal Wagle (Sports)</a>, <a href="<?php echo STANDARD_URL . 'user/jal08'; ?>">Joe Letts (Editor)</a>
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
				<div class="info-right">
					<div class="fb-like" data-href="http://felixonline.co.uk/varsity-2014" data-width="100" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
					<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://felixonline.co.uk/varsity-2014" data-via="feliximperial" data-hashtags="impvarsity" data-text="I'm watching Varsity via the Felix and @stoictv live blog">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>
			</div>
		</div>
		<div class="content row">
            <div class="col-md-6 mediabox">
            <iframe width="100%" scrolling="no" height="480" align="middle" frameborder="0" border="0" allowfullscreen="true" src="http://galen.media.su.ic.ac.uk/stoic/stream/"></iframe>
            </div>
			<div class="col-md-6 feed">
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
	<script src="//cdnjs.cloudflare.com/ajax/libs/sockjs-client/0.3.4/sockjs.min.js"></script>
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
