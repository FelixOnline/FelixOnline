<!-- Footer -->
    <div class="container_12">
        <div class="grid_12 footer"> <div class="grid_6 alpha">
                <img src="<?php echo THEME_URL; ?>/img/title-small.jpg"/>
            </div>
            <div class="grid_6 details alpha">
                <p>Felix, Beit Quad, Prince Consort Road, London SW7 2BB</p>
                <p>Email: <?php echo hideEmail('felix@imperial.ac.uk');?> Tel: 020 7594 8072 Fax: 020 7594 8065</p>
                <p>Webdesign by <a href="http://felixonline.co.uk/user/jk708/">Jonathan Kim</a>, <a href="http://felixonline.co.uk/user/pk1811/">Philip Kent</a>, and <a href="http://www.cjbirkett.co.uk/" target="_BLANK">Chris Birkett</a></p>
                <p>&copy; Felix Imperial <?php echo romanNumerals(date('Y')); ?> <a href="#topBarCont">Top of page</a></p>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo $theme->getURL();?>/js/libs/jquery-1.7.2.min.js"%3E%3C/script%3E'))</script>

    <!-- JS files -->
    <?php foreach($theme->resources->getJS() as $key => $value) { ?>
        <script src="<?php echo $value; ?>"></script>
    <?php } ?>

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
