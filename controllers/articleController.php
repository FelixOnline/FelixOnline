<?php
    
class ArticleController extends BaseController {
    function GET($matches) {
        echo 'article';
        $this->theme->render('article', $matches);
    }
}

?>
