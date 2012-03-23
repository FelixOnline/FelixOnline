<?php

class ThemeClassic extends Theme {
    function __construct($name) {
        parent::__construct($name);
    }

    public function foobar() {
        return 'Foo Bar';
    }
}
?>
