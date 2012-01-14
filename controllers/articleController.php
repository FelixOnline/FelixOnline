<?php
    
class ArticleController extends BaseController {
    function GET($matches) {
        $this->theme->render('article', $matches);
    }
}

?>
