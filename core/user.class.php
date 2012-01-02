<?php
/*
 * User class
 *
 * Fields:
 *      user        -
 *      name        -
 *      visits      -
 *      ip          -
 *      timestamp   -
 *      role        -
 *      description -
 *      email       -
 *      facebook    -
 *      twitter     -
 *      websitename -
 *      websiteurl  -
 *      img         -
 */
class User extends BaseModel {
    protected $db;

    function __construct($uname = NULL) {
        /* initialise db connection and store it in object */
        global $db;
        $this->db = $db;
        if($uname !== NULL) {
            $sql = "SELECT `user`,`name`,`visits`,`ip`,UNIX_TIMESTAMP(`timestamp`) as timestamp,`role`,`description`,`email`,`facebook`,`twitter`,`websitename`,`websiteurl`,`img` FROM `user` WHERE user=".$uname;
            $this->fields = parent::__construct($this->db->get_row($sql));
            //$this->db->cache_queries = false;
            return $this;
        } else {
        }
    }
}
?>
