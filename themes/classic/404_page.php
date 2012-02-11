<?php
$timing->log('404 error');

$header = array(
    'title' => 'Felix Online - The student voice of Imperial College London',
    'meta' => '<meta property="og:image" content="http://felixonline.co.uk/img/title.jpg"/>'
);

$theme->render('header', $header); 
$timing->log('after header');

?>
	<!-- Article wrapper -->
	<div class="container_12">
		<div class="grid_12 error">
			<h2>Lost Cat</h2>
			<img src="/img/felix_cat-300.jpg" id="lostcat" width="300px"/>
			<div class="grid_8 push_2">
				<p>Medium sized black and white cat, responds to "Felix". Likes to party and find entertaining gifs online. Mostly house trained but can bite without warning.</p>
				<p>If found then please <a href="/contact/">contact us</a> or you can try searching for it here: </p>
				<form action="/search/" id="cse-search-box">
					<input type="text" name="q" size="31" id="searchBox" class="faded" autocomplete="off" onclick="if(this.value == 'Search Felix Online...') {this.value=''; this.style.color='#222';}" onblur="if(this.value.length == 0){ this.value='Search Felix Online...'; this.style.color='#999';};" value="Search Felix Online..."/>
					<input type="submit" name="sa" value="" id="searchButton"/>
				</form>
				<p>Or just return to the <a href="/">homepage</a> to begin again.</p>
			</div>
			
			<!--<p>Seems you have stumbled on a page that isn't here! Before you ponder about the philosophical implications of that sentence why not go back the safety of the <a href="/">homepage</a> or search for what you were looking for using this lovely search box: </p>-->
			<!-- Google search -->
			<!--<form action="/search/" id="cse-search-box">
				<input type="text" name="q" size="31" id="searchbox"/>
				<input type="submit" name="sa" value="Search" id="searchbut" />
			</form>
			<p>Alternatively just enjoy the following video: </p>
			<iframe title="YouTube video player" class="youtube-player" type="text/html" width="620" height="495" src="http://www.youtube.com/embed/QgkGogPLacA" frameborder="0"></iframe>-->
		</div>
		<div class="clear"></div>
	</div>
	<!-- End of article wrapper -->

<?php $theme->render('footer'); ?>
