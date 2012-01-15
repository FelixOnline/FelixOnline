<?php
    
class ArticleController extends BaseController {
    function GET($matches) {
        $article = new Article($matches['id']);
        $this->theme->appendData(array('article' => $article));
        $this->theme->render('article');
    }
}

?>
