<?php
    if(!$currentuser->isLoggedIn()) {
      $theme->render('components/modals/box_login', array('nomodal' => false));
    }
?>

    <div id="search-dropdown" data-dropdown-content class="f-dropdown medium content" aria-autoclose="false" aria-hidden="true" tabindex="-1">
      <div class="row">
        <div class="large-12 columns">
          <form action="<?php echo STANDARD_URL; ?>search/" method="GET">
            <div class="row collapse">
              <div class="small-10 columns">
                <input type="text" name="q" placeholder="Search for something">
              </div>
              <div class="small-2 columns">
                <button type="submit" class="button search-button postfix">Search</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Commenting policy -->
    <?php $theme->render('components/modals/box_comment_policy'); ?>
    <!-- End of commenting policy -->

    <footer>
      <div class="row full-width">
        <div class="small-12 medium-10 columns clearfix small-only-text-center medium-text-left">
          <a href="#"><img src="<?php echo STANDARD_URL; ?>themes/modern/img/logo1.svg" class="svg" style="height: 4.0rem;"></a>
          <ul class="footer-links inline-list">
            <li><a href="<?php echo STANDARD_URL; ?>contact/">Contact</a></li>
            <li><a href="<?php echo STANDARD_URL; ?>contribute/">Get Involved</a></li>
            <li><a href="<?php echo STANDARD_URL; ?>policies/">Complaints and Policies</a></li>
            <li><a href="<?php echo STANDARD_URL; ?>topic">All Topics</a></li>
            <li><a href="<?php echo \FelixOnline\Core\Settings::get('app_api'); ?>">API</a></li>
            <li><a href="http://www.github.com/FelixOnline">GitHub</a></li>
          </li>
        </div>
        <div class="small-12 medium-2 columns">
          <div class="small-only-text-center medium-text-right">
            <a href="<?php echo \FelixOnline\Core\Settings::get('contact_email'); ?>"><span class="social social-e-mail"></span></a>
            <a href="<?php echo \FelixOnline\Core\Settings::get('contact_fb'); ?>"><span class="social social-facebook"></span></a>
            <a href="<?php echo \FelixOnline\Core\Settings::get('contact_twitter'); ?>"><span class="social social-twitter"></span></a>
          </div>
        </div>
      </div>

      <div class="row full-width">
        <div class="small-12 medium-6 small-only-text-center columns">
          <?php echo \FelixOnline\Core\Settings::get('contact_copyright'); ?>
        </div>
        <div class="small-12 medium-6 medium-text-right small-only-text-center columns">
          <?php echo \FelixOnline\Core\Settings::get('contact_address'); ?>
        </div>
      </div>
    </footer>

		<?php foreach($theme->resources->getJS() as $key => $value) { ?>
			<script src="<?php echo $value; ?>"></script>
		<?php } ?>
		<script>
      function init_foundation() {
  			$(document).foundation({
          equalizer : {
            equalize_on_stack: true
          }
        });
      }

      init_foundation();

			$(document).ready(function(){
				$('.carousel-block').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed: 2000,
					arrows: false,
					dots: true
				})
			});

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '<?php echo \FelixOnline\Core\Settings::get('app_ga'); ?>']);
			_gaq.push(['_trackPageview']);

			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=<?php echo \FelixOnline\Core\Settings::get('app_fb'); ?>";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();

			!function(g,s,q,r,d){r=g[r]=g[r]||function(){(r.q=r.q||[]).push( arguments)};d=s.createElement(q);q=s.getElementsByTagName(q)[0]; d.src='//d1l6p2sc9645hc.cloudfront.net/tracker.js';q.parentNode. insertBefore(d,q)}(window,document,'script','_gs'); _gs('<?php echo \FelixOnline\Core\Settings::get('app_gs'); ?>');

			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

		</script>
	</body>
</html>
