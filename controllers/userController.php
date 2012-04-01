<?php

class UserController extends BaseController {
    function GET($matches) {
        $user = new User($matches['user']);
        if(!$matches['page']) {
            $pagenum = 1;
        } else {
            $pagenum = $matches['page'];
        }
        $articles = $user->getArticles($pagenum);

        $this->theme->appendData(array(
            'user' => $user,
            'pagenum' => $pagenum,
            'articles' => $articles
        ));
        $this->theme->setHierarchy(array(
            'user' /* user-{user}.php */
        ));
        $this->theme->render('user');
    }
}
?>
