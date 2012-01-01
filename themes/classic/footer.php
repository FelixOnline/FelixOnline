    <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.5.2.min.js"%3E%3C/script%3E'))</script>

    <!-- JS files -->
    <?php foreach($this->resources->getJS() as $key => $value) { ?>
        <script src="<?php echo $value; ?>"></script>
    <?php } ?>
</body>
</html>
