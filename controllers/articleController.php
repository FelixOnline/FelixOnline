<?php
    
class ArticleController extends BaseController {
    private $article;

    function GET($matches) {
        $this->article = new Article($matches['id']);
        $this->theme->appendData(array(
            'article' => $this->article
        ));
        $this->theme->setHierarchy(array(
            'id', /* article-{id}.php */
            'category-cat' /* article-{cat}.php */
        ));
        $this->theme->setParentPage('article');
        $this->theme->render('article');
    }
}

?>
