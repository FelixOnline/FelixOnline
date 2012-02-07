<?php
/*
 * Action hooks
 *
 * Usage:
 *      $hooks->addAction('UNIQUE_IDENTIFIER', 'FUNCTION_NAME');
 *      $funcname = $hooks->getAction('UNIQUE_IDENTIFIER');
 *      call_user_func($funcname);
 */
class Hooks {
    private $actions = array(); // stores actions

    /*
     * Public: Add action
     */
    public function addAction($action, $function) {
        $this->actions[$action] = $function;
        return $this->actions;
    }

    public function getAction($action) {
        if(array_key_exists($action, $this->actions)) {
            return $this->actions[$action];
        } else {
            return false;
        }
    }
}

?>
