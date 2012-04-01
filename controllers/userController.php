<?php

class UserController extends BaseController {
    function GET($matches) {
        $user = new User($matches['user']);
        if(!$matches['page']) {
            $pagenum = 1;
        } else {
            $pagenum = $matches['page'];
        }

        $this->theme->appendData(array(
            'user' => $user,
            'pagenum' => $pagenum
        ));
        $this->theme->setHierarchy(array(
            'user' /* user-{user}.php */
        ));
        $this->theme->render('user');
    }
}
?>
