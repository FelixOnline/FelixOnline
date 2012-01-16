<?php
    
class CategoryController extends BaseController {
    function GET($matches) {
        $category = new Category($matches['cat']);
        $this->theme->appendData(array(
            'category' => $category,
            'pagenum' => $matches['page']
        ));
        $this->theme->render('category');
    }
}

?>
