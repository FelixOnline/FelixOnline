<?php

/* 
 * Sets up the Felix Online environment 
 *
 * TODO: 
 *      sort this MESS OUT 
 *      add authentication script 
 * 
 */

// define current working directory
if(!defined('BASE_DIRECTORY')) define('BASE_DIRECTORY', realpath(dirname(__FILE__).'/../'));

require_once(dirname(__FILE__).'/config.inc.php');
//require_once('article.inc.php');
require_once(dirname(__FILE__).'/const.inc.php');
require_once(dirname(__FILE__).'/../core/email.inc.php');
require_once(dirname(__FILE__).'/../core/comment.inc.php');
require_once(dirname(__FILE__).'/rss.inc.php');

function global_text($sect,$return) { // 0 stripped value, 1 array
    global $dbok,$cid;
    if ($dbok && $sect) {
        $sql = "SELECT value,style FROM `text_global` WHERE `key`='$sect' LIMIT 1";
        $txt = mysql_fetch_array(mysql_query($sql,$cid));
        switch ($return) {
            case (0):
                return $txt['value'];
            case (1):
                return $txt;
            default:
                return "";
        }
    }
    else
        return "";
}

function get_article_title($id) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT title FROM `article` WHERE id=$id";
        return mysql_result(mysql_query($sql,$cid),0);
    }
}

function get_short_article_title($id) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT short_title FROM `article` WHERE id=$id";
        if ($title = mysql_result(mysql_query($sql,$cid),0))
            return $title;
        else
            return get_article_title($id);
    }
}

function get_short_article_desc($id) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT short_desc FROM `article` WHERE id=$id";
        //if ($desc = mysql_result(mysql_query($sql,$cid),0))
            //return $desc;
        //else
        return trim(get_article_preview($id, 80));
    }
}

function get_article_teaser($id) { // Article DONE
    global $cid;
    $sql = "SELECT teaser,content AS text1 FROM `article` INNER JOIN `text_story` ON (article.text1=text_story.id) WHERE article.id=$id";
    list($teaser,$content) = mysql_fetch_array(mysql_query($sql,$cid));
    if ($teaser)
        return str_replace('<br/>','',strip_tags($teaser));
    else {
        return trim(substr(strip_tags($content),0,strrpos(substr(strip_tags($content),0,TEASER_LENGTH),' '))).'...';
    }
}

function get_article_preview($id, $length=170) { // Article DONE
    global $dbok,$cid;
    $search = array('@<>@',
        '@<script[^>]*?>.*?</script>@siU',  // Strip out javascript
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<embed[^>]*?>.*?</embed>@siU',    // embed
        '@<object[^>]*?>.*?</object>@siU',    // object
        '@<iframe[^>]*?>.*?</iframe>@siU',    // iframe
        '@<![\s\S]*?--[ \t\n\r]*>@',        // Strip multi-line comments including CDATA
        '@</?[^>]*>*@'        // html tags
    );
    if ($dbok) {
        $sql = "SELECT content FROM `article` INNER JOIN `text_story` ON (article.text1=text_story.id) WHERE article.id=$id";
        $content = mysql_result(mysql_query($sql,$cid),0);
        if (strlen($content) <= $length)
            return strip_tags($content);
        else
            return substr(preg_replace($search,'',$content),0,strrpos(substr($content,0,$length),' ')).' <a href="'.article_url($id).'" title="Read more">...</a>';
    }
}

function get_article_preview2($id, $limit) {
    global $dbok,$cid;

    $sql = "SELECT content FROM `article` INNER JOIN `text_story` ON (article.text1=text_story.id) WHERE article.id=$id";
    $string = mysql_result(mysql_query($sql,$cid),0);

    $string = strip_tags($string);

    if(strlen($string) <= $limit) return $string;
    else $string = substr($string, 0, $limit) . ' <a href="'.article_url($id).'" title="Read more">...</a>';

    // if(false !== ($breakpoint = strpos($string, $break, $limit))) {
        // if($breakpoint < strlen($string) - 1) {
            // $string = substr($string, 0, $breakpoint) . ' <a href="page.php?article='.$id.'" title="Read more">...</a>';
        // }
    // }

    return $string;
}

function get_article_preview_trunc($id, $wordlimit) {
    global $dbok,$cid;

    $sql = "SELECT content FROM `article` INNER JOIN `text_story` ON (article.text1=text_story.id) WHERE article.id=$id";
    $string = mysql_result(mysql_query($sql,$cid),0);

    $string = strip_tags($string);

    $append = '';
    $words = explode(" ",$string);
    if(count($words) > $wordlimit) {
      $append = ' ... <br/><a href="'.article_url($id).'" title="Read more" id="readmorelink">Read more</a>';
    }
    return implode(" ",array_splice($words,0,$wordlimit)) . $append;
}

function get_article_text($id,$text=1) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT content FROM `article` INNER JOIN `text_story` ON (article.text$text=text_story.id) WHERE article.id=$id";
        $content = mysql_result(mysql_query($sql,$cid),0);
        return $content;
    }
}

function get_article_text_id($id,$text=1) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT text$text FROM `article` WHERE article.id=$id";
        $id = mysql_result(mysql_query($sql,$cid),0);
        return $id;
    }
}

function get_article_date($id) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT UNIX_TIMESTAMP(date) FROM `article` WHERE id=$id";
        return mysql_result(mysql_query($sql,$cid),0);
    }
}

function get_article_publisher($id) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT name from `article` AS a INNER JOIN `user` AS u ON (a.approvedby=u.user) WHERE id=$id";
        if (mysql_num_rows($rsc = mysql_query($sql)))
            return mysql_result($rsc,0);
        else
            return false;
    }
}

function get_article_publishdate($id) { // Article DONE
    global $dbok,$cid;
    if ($dbok) {
        $sql = "SELECT UNIX_TIMESTAMP(published) FROM `article` WHERE id=$id";
        return mysql_result(mysql_query($sql,$cid),0);
    }
}

function get_article_img_uri($id,$img=1) {
    global $dbok,$cid;
    if ($dbok && is_numeric($id)) {
        $sql = "SELECT uri FROM `article` INNER JOIN image ON (article.img$img=image.id) WHERE article.id=$id";
        $result = mysql_query($sql,$cid);
        if (mysql_num_rows($result)) {
            if (($uri = mysql_result($result,0)) != "")
                return $uri;
            else
                return DEFAULT_IMG_URI;
        }
        else
            return DEFAULT_IMG_URI;
    }
    else
        return DEFAULT_IMG_URI;
}

function get_img_uri($id) {
    global $dbok,$cid;
    if ($dbok && is_numeric($id)) {
        $sql = "SELECT uri FROM `image` WHERE id=$id";
        $result = mysql_query($sql,$cid);
        if (mysql_num_rows($result)) {
            if (($uri = mysql_result($result,0)) != "")
                return $uri;
            else
                return DEFAULT_IMG_URI;
        }
        else
            return DEFAULT_IMG_URI;
    }
    else
        return DEFAULT_IMG_URI;
}

/*
 * Get's full image url from image id and width (optional) and height (optional)
 * TODO: Change default?
 */
function get_img_url($id, $width = '', $height = '') {
    global $dbok,$cid;
    if ($dbok && is_numeric($id)) {
        $sql = "SELECT uri FROM `image` WHERE id=$id";
        $result = mysql_query($sql,$cid);
        if (mysql_num_rows($result)) {
            if (($uri = mysql_result($result,0)) != "") {
                $uri = str_replace('img/upload/', '', $uri);
                if($height) {
                    return IMAGE_URL.$width.'/'.$height.'/'.$uri;
                } else if($width) {
                    return IMAGE_URL.$width.'/'.$uri;
                } else {
                    return IMAGE_URL.'upload/'.$uri;
                }
            } else
                return IMAGE_URL.DEFAULT_IMG_URI;
        }
        else
            return IMAGE_URL.DEFAULT_IMG_URI;
    }
    else
        return IMAGE_URL.DEFAULT_IMG_URI;
}

function get_article_category($id) {
    global $cid;
    $sql = "SELECT label FROM `article` INNER JOIN `category` ON (article.category=category.id) WHERE article.id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_article_category_cat($id) {
    global $cid;
    $sql = "SELECT cat FROM `article` INNER JOIN `category` ON (article.category=category.id) WHERE article.id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_article_category_uri($id) {
    global $cid;
    $sql = "SELECT uri FROM `article` INNER JOIN `category` ON (article.category=category.id) WHERE article.id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_article_category_id($id) {
    global $cid;
    $sql = "SELECT category FROM `article` WHERE article.id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_article_colourclass($id) {
    global $cid;
    $sql = "SELECT colourclass FROM `article` INNER JOIN `category` ON (article.category=category.id) WHERE article.id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_category_colourclass($cat) {
    global $cid;
    $sql = "SELECT colourclass FROM `category` WHERE cat='$cat'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_img_title($id) {
    global $cid;
    $sql = "SELECT title FROM image WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_img_id($article,$img) {
    global $cid;
    if ($article) {
        $sql = "SELECT img$img FROM `article` WHERE id=$article";
        return mysql_result(mysql_query($sql,$cid),0);
    } else {
        return false;
    }
}

function get_img_desc($id) {
    global $cid;
    $sql = "SELECT description FROM `image` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_img_v_offset($id) {
    global $cid;
    $sql = "SELECT v_offset FROM `image` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_img_h_offset($id) {
    global $cid;
    $sql = "SELECT h_offset FROM `image` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_img_caption($id) {
    global $cid;
    $sql = "SELECT caption FROM `image` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid), 0);
}

function get_img_attr($id) {
    global $cid;
    $sql = "SELECT attribution FROM `image` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_img_attr_link($id) {
    global $cid;
    $sql = "SELECT attr_link FROM `image` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function curPageURLSecure() {
    $pageURL = 'https://';
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

function curPageURLNonSecure() {
    return 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}

function login($username) {
    if (!($_SESSION['felix']['vname'] = get_vname_by_uname_ldap($username)))
        return false;
    $_SESSION['felix']['name'] = get_forename($username);
    $_SESSION['felix']['uname'] = $username;
    $_SESSION['felix']['loggedin'] = true;
    destroy_old_sessions($username);
    update_login_name($username,$_SESSION['felix']['vname']);
    return true;
}

function re_login($username) {
    if (!($_SESSION['felix']['vname'] = get_vname_by_uname_ldap($username)))
        return false;
    $_SESSION['felix']['name'] = get_forename($username);
    $_SESSION['felix']['uname'] = $username;
    $_SESSION['felix']['loggedin'] = true;
    return true;
}

function login_admin($username) {
    if (!($_SESSION['felix-admin']['vname'] = get_vname_by_uname_ldap($username)))
        return false;
    $_SESSION['felix-admin']['name'] = get_forename($username);
    $_SESSION['felix-admin']['uname'] = $username;
    $_SESSION['felix-admin']['loggedin'] = true;
    destroy_old_sessions($username);
    update_login_name($username,$_SESSION['felix-admin']['vname']);
    return true;
}

function logout() {
    if ($user = $_SESSION['felix']['uname'])
        destroy_sessions($user);
    $_SESSION['felix']['loggedin'] = false;
    if(isset($_COOKIE['felixonline']))
        setcookie("felixonline", "", time(), "/");
    if(isset($_COOKIE['felixonlinesession']))
        setcookie("felixonlinesession", "", time(), "/");
}

function logout_admin() {
    if ($user = $_SESSION['felix-admin']['uname'])
        destroy_sessions($user);
    $_SESSION['felix-admin']['loggedin'] = false;
}

function is_logged_in() { // returns active user or false
    if ($_SESSION['felix']['loggedin'])
        return $_SESSION['felix']['uname'];
    else
        return false;
}

function is_logged_in_admin() { // returns active user or false
    if ($_SESSION['felix-admin']['loggedin'])
        return $_SESSION['felix-admin']['uname'];
    else
        return false;
}

function get_name() {
    return $_SESSION['felix']['name'];
}

function get_vname() {
    return $_SESSION['felix']['vname'];
}

function get_vname_admin() {
    return $_SESSION['felix-admin']['vname'];
}

function get_vname_by_uname_ldap($uname) {
    if(!LOCAL) { // if on union server
        $ds=ldap_connect("addressbook.ic.ac.uk");
        $r=ldap_bind($ds);
        $justthese = array("gecos");
        $sr=ldap_search($ds, "ou=People, ou=everyone, dc=ic, dc=ac, dc=uk", "uid=$uname", $justthese);
        $info = ldap_get_entries($ds, $sr);
        if ($info["count"] > 0)
            return $info[0]['gecos'][0];
        else
            return false;
    } else {
        return $uname;
    }
}

function get_user_info_by_uname_ldap($uname) {
    if(!LOCAL) { // if on union server
        $ds=ldap_connect("addressbook.ic.ac.uk");
        $r=ldap_bind($ds);
        $justthese = array("o");
        $sr=ldap_search($ds, "ou=People, ou=shibboleth, dc=ic, dc=ac, dc=uk", "uid=$uname", $justthese);
        $info = ldap_get_entries($ds, $sr);
        if ($info["count"] > 0)
            return $info[0]['o'][0];
        else
            return false;
    } else {
        return $uname;
    }
}

function get_vname_by_uname_db($uname) {
    global $cid;
    $sql = "SELECT name FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_uname_by_vname_db($vname) {
    global $cid;
    $sql = "SELECT user FROM `user` WHERE name='$vname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_user_role($uname) {
    global $cid;
    $sql = "SELECT role FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_role_level($role) {
    global $cid;
    $result = mysql_query("SELECT id FROM `role` WHERE role='$role'",$cid);
    if (mysql_num_rows($result))
        return mysql_result($result,0);
    else
        return false;

}

function get_role_name($id) {
    global $cid;
    $result = mysql_query("SELECT role FROM `role` WHERE id='$id'",$cid);
    if (mysql_num_rows($result))
        return mysql_result($result,0);
    else
        return false;

}

function get_forename($uname) {
    if(!LOCAL) {
        $ds=ldap_connect("addressbook.ic.ac.uk");
        $r=ldap_bind($ds);
        $justthese = array("givenname");
        $sr=ldap_search($ds, "o=Imperial College, c=GB", "uid=$uname", $justthese);
        $info = ldap_get_entries($ds, $sr);
        if ($info["count"] > 0)
            return $info[0][givenname][0];
        else
            return $uname;
    } else {
        return $uname;
    }
}

function convert_to_columns($content, $columns) {
    $length = strlen($content);
    if ($length < 30) {
        $text[0] = $content;
        return $text;
    }
    $words = count($wordarray = explode(' ',$content));
    $charsincol = ceil($length/$columns);
    $lastbreak = 0;
    $charctr = 0;
    $wordctr = 0;
    $col = 0;
    $charspercol = ceil($length/$columns);
    while ($charctr < $length) {
        if (($charctr-$lastbreak) >= $charsincol) {
            $lastbreak = $charctr;
            $col++;
        }
        $text[$col] .= $wordarray[$wordctr].' ';
        $charctr += (strlen($wordarray[$wordctr])+1);
        $wordctr++;
    }
    $text[$col] = substr($text[$col],0,-1);
    return $text;
}

function set_session($session,$user) {
    global $cid;
    $user = strtolower($user);
    if ($session != "" && $user != "")
        if (mysql_query("INSERT INTO `login` (session_id,user) VALUES ('$session','$user')",$cid))
            return true;
    return false;
}

function get_user_from_session($session) {
    global $cid;
    $sql = "SELECT user FROM `login` WHERE session_id='$session' ORDER BY id DESC LIMIT 1";
    return mysql_result(mysql_query($sql,$cid),0);
}

function is_session_recent($session) {
    global $cid;
    $sql = "SELECT TIMESTAMPDIFF(SECOND,timestamp,NOW()) AS timediff FROM `login` WHERE session_id='$session' AND valid=1 ORDER BY timediff ASC LIMIT 1";
    $result = mysql_query($sql,$cid);
    if (mysql_num_rows($result)) {
        $session_age = mysql_result($result,0);
        return ($session_age <= SESSION_LENGTH);
    }
    else
        return false;
}

/*
 * Check if session id stored in cookie is recent
 */
function is_session_cookie_recent($session) {
    global $cid;
    $sql = "SELECT TIMESTAMPDIFF(SECOND,timestamp,NOW()) AS timediff FROM `login` WHERE session_id='$session' AND valid=1 ORDER BY timediff ASC LIMIT 1";
    $result = mysql_query($sql,$cid);
    if (mysql_num_rows($result)) {
        $session_age = mysql_result($result,0);
        return ($session_age <= COOKIE_LENGTH);
    }
    else
        return false;
}

function destroy_sessions($user) {
    global $cid;
    return mysql_query("UPDATE login SET valid=0 WHERE user='$user'",$cid);
}

function destroy_old_sessions($user) {
    global $cid;
    return mysql_query("UPDATE login SET valid=0 WHERE user='$user' AND timestamp < TIMESTAMPADD(SECOND,-30,NOW())",$cid);
}

function get_category($cat) {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `category` WHERE cat='$cat'";
    if (mysql_result(mysql_query($sql,$cid),0) == 1)
        return $cat;
    else
        return 'frontpage';
}

function check_article($id) {
    if (!is_numeric($id))
        return false;
    global $cid;
    $sql = "SELECT COUNT(*) FROM `article` WHERE id=$id AND text1 IS NOT NULL AND img1 IS NOT NULL";
    return (mysql_result(mysql_query($sql,$cid),0) == 1);
}

function check_article2($id) {
    if (!is_numeric($id))
        return false;
    global $cid;
    $sql = "SELECT COUNT(*) FROM `article` WHERE id=$id AND text1 IS NOT NULL";
    return (mysql_result(mysql_query($sql,$cid),0) == 1);
}

function check_user($user) {
    if (!$user)
        return false;
    global $cid;
    $sql = "SELECT COUNT(*) FROM `user` WHERE user='$user'";
    return (mysql_result(mysql_query($sql,$cid),0) == 1);
}

function check_category($category) {
    if (!is_numeric($category) || $category==0)
        return false;
    global $cid;
    $sql = "SELECT COUNT(*) FROM `category` WHERE id=$category";
    return (mysql_result(mysql_query($sql,$cid),0) == 1);
}

function check_img($img) {
    if (!is_numeric($img))
        return false;
    global $cid;
    return mysql_num_rows(mysql_query("SELECT id FROM image WHERE id=$img",$cid));
}

function update_login_name($user,$name) {
    global $cid;
    $ip = $_SERVER['REMOTE_ADDR'];
    $name = trim($name);
    $sql = "INSERT INTO `user` (user,name,visits,ip) VALUES ('$user','$name',1,'$ip') ON DUPLICATE KEY UPDATE name='$name',visits=visits+1,ip='$ip',timestamp=NOW()";
    return($user && $name && mysql_query($sql,$cid));
}

/*
 * Comments
 */

/*
 * Depreciated: Check if comment exists in database
 *
 * $article - id of article that comment relates to
 * $user    - username of commenter 
 * $comment - text of comment submitted
 *
 * Returns the number of rows that the query will return
 *  i.e. 0 for none found. >0 if found
 *
 * TODO
 *  - Make similar check of external comments
 */
function comment_exists($article,$user,$comment) {
    global $cid;
    $sql = "SELECT id FROM `comment` WHERE article=$article AND user='$user' AND comment='$comment' AND `active`=1";
    return (mysql_num_rows(mysql_query($sql,$cid)));
}

/*
 * Insert comment into database (Imperial user)
 *
 * $article         - id of article that the comment relates to
 * $user            - username of commenter
 * $comment         - text of comment submitted
 * $replyName       - commenter's name of comment that this comment is replying to
 * $replyCommentID    - id of comment that this comment is replying to
 *
 * Returns id of inserted comment
 */
function insert_comment($article,$user,$comment,$replyName,$replyCommentID) {
    global $cid;

    $sql = "INSERT INTO `comment` (article,user,comment,reply) VALUES ('$article','$user','$comment','$replyCommentID')"; // insert comment comment into database
    mysql_query($sql,$cid) or die(mysql_error()); // execute mysql query
    $commentid = mysql_insert_id(); // get id of inserted comment

    if($replyCommentID) // if comment is replying to a comment 
        email_comment_reply($article,$user,$comment,$commentid,$name,$replyCommentID); // email the user of that comment

    email_article_comment($article,$user,$comment,$commentid); // email comment to authors of article
    return $commentid; // return comment id
}

/*
 * Email authors of article new comment
 *
 * $article         - id of article that the comment relates to
 * $user            - username of commenter
 * $comment         - text of comment submitted
 * $commentid       - id of comment
 * $name            - name of commenter [defaults to NULL if none provided]
 *
 * Returns true...always [TODO]
 */
function email_article_comment($article,$user,$comment,$commentid,$name=NULL) {
    global $cid;

    // Get authors email address and names
    $authors = get_article_authors_uname($article);
    $to = array();
    foreach($authors as $author) {
        $to['vname'][] = get_vname_by_uname_db($author);
        if(!($email = get_user_email($author)) && !LOCAL) {
            $email = ldap_get_mail($author);
        }
        $to['email'][] = $email;
    }

    $body = '';
    $subject = '';

    if($user){
        $body .= '<a href="'.BASE_URL.'user/'.$user.'">'.get_vname_by_uname_db($user).'</a>';
        $subject .= get_vname_by_uname_db($user);
    } else if($name) {
        $body .= $name;
        $subject .= $name;
    } else {
        $body .= 'Someone';
        $subject .= 'Someone';
    }

    $body .= ' has posted a comment on "<a href="'.BASE_URL.article_url($article).'#'.$commentid.'/">'.get_article_title($article_id).'</a>" <br/><br/>';
    $body .= '"'.stripslashes(str_replace('\r\n',"\n",str_replace(array('\\\\\\','&lt;','&gt;','\\&quot;','&amp;','Â'),array('','<','>','"','&',''),$comment))).'"'."<br/><br/>";
    $body .= "<a href='".BASE_URL.article_url($article).'#'.$commentid."'>View Comment</a><br/><br/>";
    //$body .= "<a href='".BASE_URL."engine/?page=comment&action=trash&c=".$commentid."'>Trash comment</a><br/><br/>";
    $body .= "Lots of love,<br/>";
    $body .= "Felix<br/>";

    $subject .= ' has commented on '.get_article_title($article);

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $headers .= "From: ".EMAIL_FROM_ADDR."\r\n" .
    'Reply-To: '.EMAIL_REPLYTO_ADDR."\r\n" .
    'X-Mailer: PHP/' . phpversion();
    foreach ($to['email'] as $key => $email) {
        $firstname = explode(' ', $to['vname'][$key]);
        $bodysend = 'Hi '.$firstname[0].'<br/><br/>'.$body;
        mail($email, $subject, $bodysend, $headers);
    }
    return true;
}

/*
 * Depreciated
 */
function email_comment_reply($article_id,$user,$comment,$commentid,$name=NULL,$replyComment) {
    global $cid;

    // get original commenter's email (only from internal commenter)
    $sql = "SELECT user FROM comment WHERE id='$replyComment'";
    $result = mysql_query($sql,$cid);

    if(mysql_num_rows($result)) { // if replied to comment was done by user then send email to user
        $orig_user= mysql_result($result,0);
        $to = get_user_email_full($orig_user);

        $body = '';
        $subject = '';

        if($user){ // if commenter is a user the add name
            $body .= '<a href="'.BASE_URL.'user/'.$user.'">'.get_vname_by_uname_db($user).'</a>';
            $subject .= get_vname_by_uname_db($user);
        } else if($name) { // else if not a user but left name
            $body .= $name;
            $subject .= $name;
        } else { // else annonymous
            $body .= 'Someone';
            $subject .= 'Someone';
        }

        $body .= ' has replied to your comment on "<a href="'.BASE_URL.article_url($article_id).'#'.$replyComment.'/">'.get_article_title($article_id).'</a>" with:<br/><br/>';
        $body .= '<a href="'.BASE_URL.article_url($article_id).'#'.$commentid.'">@'.get_vname_by_uname_db($orig_user).'</a><br/>';
        $body .= '"'.stripslashes(str_replace('\r\n',"\n",str_replace(array('\\\\\\','&lt;','&gt;','\\&quot;','&amp;','Â'),array('','<','>','"','&',''),$comment))).'"'."<br/><br/>";
        $body .= "<a href='".BASE_URL.article_url($article_id).'#'.$commentid."'>View Comment</a><br/><br/>";
        $body .= "Lots of love,<br/>";
        $body .= "Felix<br/>";

        $subject .= ' has replied to your comment on '.get_article_title($article_id);

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $headers .= "From: ".EMAIL_FROM_ADDR."\r\n" .
        'Reply-To: '.EMAIL_REPLYTO_ADDR."\r\n" .
        'X-Mailer: PHP/' . phpversion();

        $firstname = explode(' ', get_vname_by_uname_db($orig_user));

        $bodysend = 'Hi '.$firstname[0].'<br/><br/>'.$body;
        mail($to, $subject, $bodysend, $headers);

        return true;
    } else
        return false;
}

function insert_comment_ext($article,$name,$comment,$replyName,$replyComment) {
    global $cid;
    // check for spam
    if(check_spam($_SERVER['REMOTE_ADDR'])) {
        if($replyComment) { // if reply url
            $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply) VALUES ('$article','$name','$comment',0,'".$_SERVER['REMOTE_ADDR']."',0,'$replyComment')";
        } else {
            $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending) VALUES ('$article','$name','$comment',0,'".$_SERVER['REMOTE_ADDR']."',0)";
        }
        return true;
    } else {
        if($replyComment) { // if reply url
            $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply) VALUES ('$article','$name','$comment',1,'".$_SERVER['REMOTE_ADDR']."',1,'$replyComment')";
        } else {
            $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending) VALUES ('$article','$name','$comment',1,'".$_SERVER['REMOTE_ADDR']."',1)";
        }

        mysql_query($sql,$cid) or die(mysql_error());
        $commentid = mysql_insert_id();
        return (notify_felix_of_ext_comment($article,$comment,$commentid,$name,$replyName,$replyComment));
    }
}

function check_spam($ip) {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `comment_spam` WHERE IP = '$ip' AND date > NOW()";
    return mysql_result(mysql_query($sql, $cid), 0);
}

function insert_comment_spam($article,$name,$comment,$replyName,$replyComment) {
    global $cid;

    if($replyComment) { // if reply url
        $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply,spam) VALUES ('$article','$name','$comment',0,'".$_SERVER['REMOTE_ADDR']."',0,'$replyComment',1)";
    } else {
        $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,spam) VALUES ('$article','$name','$comment',0,'".$_SERVER['REMOTE_ADDR']."',0,1)";
    }

    mysql_query($sql,$cid) or die(mysql_error());
    $id = mysql_insert_id();

    // insert comment ip into comment_spam
    $sql = "INSERT IGNORE INTO `comment_spam` (IP, date) VALUES ('".$_SERVER['REMOTE_ADDR']."', DATE_ADD(NOW(), INTERVAL 2 MONTH))";
    mysql_query($sql,$cid) or die(mysql_error());

    return $id;
}

function insert_comment_external($article,$name,$comment,$replyName,$replyComment) {
    global $cid;

    if($replyComment) { // if reply url
        $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending,reply) VALUES ('$article','$name','$comment',1,'".$_SERVER['REMOTE_ADDR']."',1,'$replyComment')";
    } else {
        $sql = "INSERT INTO `comment_ext` (article,name,comment,active,IP,pending) VALUES ('$article','$name','$comment',1,'".$_SERVER['REMOTE_ADDR']."',1)";
    }

    mysql_query($sql,$cid) or die(mysql_error());
    $id = mysql_insert_id();

    $commentid = mysql_insert_id();
    if (notify_felix_of_ext_comment($article,$comment,$commentid,$name,$replyName,$replyComment)) {
        return $id;
    } else {
        return false;
    }
}

function mark_spam($id) {
    global $cid;

    // get ip from comment id
    $sql = "SELECT IP FROM `comment_ext` WHERE id='$id'";
    $result = mysql_query($sql,$cid);

    if(mysql_num_rows($result)) {
        $ip = mysql_result($result, 0);

        // insert comment ip into comment_spam
        $sql = "INSERT IGNORE INTO `comment_spam` (IP, date) VALUES ('$ip', DATE_ADD(NOW(), INTERVAL 2 MONTH))";
        mysql_query($sql,$cid) or die(mysql_error());

        // mark all comments with that ip as not pending
        $sql = "UPDATE `comment_ext` SET active=0, pending=0, spam=1 WHERE IP='$ip'";
        $result = mysql_query($sql)
            or die(mysql_error());

        // check aksimet spam
        require_once('akismet.class.php');

        $WordPressAPIKey = '4c2ddc0022f0';
        $MyBlogURL = 'http://felixonline.co.uk';

        $akismet = new Akismet($MyBlogURL ,$WordPressAPIKey);

        $akismet->submitSpam();

        return true;
    } else {
        return false;
    }
}

function not_spam($id) {
    global $cid;
    // get ip from comment id
    $sql = "SELECT IP FROM `comment_ext` WHERE id='$id'";
    $result = mysql_query($sql,$cid);

    if(mysql_num_rows($result)) {
        $ip = mysql_result($result, 0);

        // delete ip from table
        $sql = "DELETE FROM `comment_spam` WHERE IP='$ip' LIMIT 1";
        mysql_query($sql,$cid) or die(mysql_error());

        // mark all comments with that ip as pending
        $sql = "UPDATE `comment_ext` SET active=1,pending=1 WHERE IP='$ip'";
        $result = mysql_query($sql)
            or die(mysql_error());

        // check aksimet spam
        require_once('akismet.class.php');

        $WordPressAPIKey = '4c2ddc0022f0';
        $MyBlogURL = 'http://felixonline.co.uk';

        $akismet = new Akismet($MyBlogURL ,$WordPressAPIKey);

        $akismet->submitHam();

        return true;
    } else {
        return false;
    }
}

function notify_felix_of_ext_comment($article,$comment,$commentid,$name,$replyName,$replyComment) {
    $headers = "From: ".EMAIL_FROM_ADDR."\r\n" .
    'Reply-To: '.EMAIL_REPLYTO_ADDR."\r\n" .
    'X-Mailer: PHP/' . phpversion();
    $to = EMAIL_EXTCOMMENT_NOTIFYADDR;
    $subject = "New comment to approve on ".get_article_title($article);
    $body = "A new comment on the post '".get_article_title($article)."' is waiting for your approval\n".STANDARD_URL.article_url($article)."\n\nAuthor : ".stripslashes($name)." (IP: ".$_SERVER['REMOTE_ADDR'].")\nWhois  : http://ip-whois-lookup.com/lookup.php?ip=".$_SERVER['REMOTE_ADDR']."\nComment:\n";
    if($replyComment)
        $body .= "@".$replyName."\n";
    $body .= '"'.stripslashes(str_replace("\\r\\n","\r\n",$comment)).'"'."\n\nApprove it: ".STANDARD_URL."engine/?page=comment&action=approve&c=".$commentid."\nTrash it: ".STANDARD_URL."engine/?page=comment&action=trash&c=".$commentid."\nSpam it: ".STANDARD_URL."engine/?page=comment&action=spam&c=".$commentid."\n\nThere are ".get_comments_to_approve()." comment(s) waiting to be approved. View them here: ".BASE_URL."engine/?page=comment\n\nFelix Online";
    return mail($to,$subject,$body,$headers);
}

function email_article_comment_ext_by_id($comment_id) {
    global $cid;
    $sql = "SELECT article,name,comment FROM comment_ext WHERE id=$comment_id LIMIT 1";
    list($article_id,$name,$comment) = mysql_fetch_array(mysql_query($sql,$cid));
    return email_article_comment($article_id,NULL,$comment,$comment_id,$name);
}

function comment_is_reply($id, $user) {
    global $cid;
    if($user == 'extuser0' || $user == 'extuser1' || $user == 'extuser2') {
        $sql = "SELECT reply FROM comment_ext WHERE id='$id'";
    } else {
        $sql = "SELECT reply FROM comment WHERE id='$id'";
    }

    $result = mysql_query($sql,$cid);

    if(mysql_num_rows($result)) {
        return mysql_result($result, 0);
    } else {
        return false;
    }
}

function get_comment_author($id, $user) {
    global $cid;
    // if($user == 'extuser0' || $user == 'extuser1' || $user == 'extuser2') {
    if($id >= 80000000) {
        $sql = "SELECT name FROM comment_ext WHERE id='$id'";
        $result = mysql_result(mysql_query($sql,$cid),0);
        if(!$result)
            $output = 'Anonymous';
        else
            $output = $result;
    } else {
        $sql = "SELECT user FROM comment WHERE id='$id'";
        $result = mysql_result(mysql_query($sql,$cid),0);
        $output = get_vname_by_uname_db($result);
    }

    return $output;
}

/*
 * 
 */
function hit_article($id) {
    global $cid;
    $sql = "UPDATE `article` SET hits=hits+1 WHERE id=$id";
    return (mysql_query($sql,$cid));
}

function hit_album($id) {
    global $cid;
    $sql = "UPDATE `media_photo_albums` SET hits=hits+1 WHERE id=$id";
    return (mysql_query($sql,$cid));
}

function hit_video($id) {
    global $cid;
    $sql = "UPDATE `media_video` SET hits=hits+1 WHERE id=$id";
    return (mysql_query($sql,$cid));
}

function count_articles_by_author($user) {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `article` WHERE author='$user' AND `published` IS NOT NULL";
    return mysql_result(mysql_query($sql,$cid),0);
}

function count_articles_by_author_all($user) {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `article_author` WHERE author='$user'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function count_comments_by_author($user) {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `comment` WHERE user='$user' AND `active`=1";
    return mysql_result(mysql_query($sql,$cid),0);
}

function count_ratings_by_author($user) {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `comment_like` WHERE user='$user'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_firstdate($user) {
    global $cid;
    $sql = "SELECT UNIX_TIMESTAMP(timestamp) FROM `login` WHERE user='$user' ORDER BY timestamp ASC LIMIT 1";
    $rsc = mysql_query($sql,$cid);
    if (mysql_num_rows($rsc))
        return mysql_result($rsc,0);
    else
        return 1262304000;
}

function get_lastdate($user) {
    global $cid;
    $sql = "SELECT UNIX_TIMESTAMP(timestamp) FROM `login` WHERE user='$user' ORDER BY timestamp DESC LIMIT 1";
    $rsc = mysql_query($sql,$cid);
    if (mysql_num_rows($rsc))
        return mysql_result($rsc,0);
    else
        return 1262304000;
}

function get_articles_by_user($user) {
    global $cid;
    $sql = "SELECT id FROM `article` WHERE author='$user' AND `published` IS NOT NULL ORDER BY date DESC";
    $articles = array();
    $result = mysql_query($sql,$cid);
    while ($row = mysql_fetch_array($result))
        $articles[] = $row[0];
    return $articles;
}

function get_article_comments_by_user($user) {
    global $cid;
    $sql = "SELECT c.article,COUNT(c.article),c.comment AS comments FROM `comment` AS c INNER JOIN `article` AS a ON (c.article=a.id) WHERE user='$user' AND c.`active`=1 GROUP BY article ORDER BY a.date DESC LIMIT 0,".NUMBER_OF_POPULAR_COMMENTS_USER;
    $comments = array();
    $result = mysql_query($sql,$cid);
    while ($row = mysql_fetch_array($result))
        $comments[] = $row;
    return $comments;
}

function get_articles_by_user_popular($user) {
    global $cid;
    $sql = "SELECT id FROM `article` WHERE author='$user' AND `published` IS NOT NULL ORDER BY hits DESC LIMIT 0,".NUMBER_OF_POPULAR_ARTICLES_USER;
    $articles = array();
    $result = mysql_query($sql,$cid);
    while ($row = mysql_fetch_array($result))
        $articles[] = $row[0];
    return $articles;
}

function get_article_hits($id) {
    global $cid;
    $sql = "SELECT hits FROM `article` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_article_comments($id) {
    global $cid;
    $sql = "SELECT SUM(count) AS count FROM (SELECT article,COUNT(*) AS count FROM `comment` WHERE article=$id AND `active`=1 GROUP BY article UNION ALL SELECT article,COUNT(*) AS count FROM `comment_ext` WHERE article=$id AND `active`=1 AND `pending`=0 GROUP BY article) AS t GROUP BY article";
    $rsc = mysql_query($sql,$cid);
    if (!mysql_num_rows($rsc))
        return 0;
    return mysql_result($rsc,0);
}

function get_article_author_uname($id) {
    global $cid;
    $sql = "SELECT author FROM `article` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_article_author_vname($id) {
    global $cid;
    $sql = "SELECT ln.name FROM `article` AS a INNER JOIN `user` AS ln ON (a.author=ln.user) WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_article_authors_uname($id) {
    global $cid;
    //$sql = "SELECT ln.name FROM `article` AS a INNER JOIN `user` AS ln ON (a.author=ln.user) WHERE id=$id";
    $sql = "SELECT article_author.author FROM `article_author` INNER JOIN `article` ON (article_author.article=article.id) WHERE article.id='$id'";
    $i = 0;
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result)){
        $authors[$i] = $row['author'];
        $i++;
    }

    return $authors;
}

function get_article_tags($id) {
    global $cid;
    $sql = "SELECT topic.name FROM `topic` INNER JOIN `article_topic` ON (article_topic.topic_id=topic.id) WHERE article_topic.article_id='$id'";
    $i = 0;
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result)){
        $tags[$i] = $row['name'];
        $i++;
    }

    return $tags;
}

function like_comment($comment,$user,$bool) {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `comment_like` WHERE user='$user' AND comment=$comment";
    if (mysql_result(mysql_query($sql,$cid),0) == 0) { // optimise me
        $sql = "INSERT INTO `comment_like` (user,comment,binlike) VALUES ('$user','$comment','".(($bool)?'1':'0')."')";
        if (mysql_query($sql,$cid))
            return true;
        else
            die(mysql_error());
    } else
        return false;
}

function get_user_comment_likes($user) {
    global $cid;
    return mysql_result(mysql_query("SELECT COUNT(*) FROM `comment_like` WHERE user='$user'",$cid),0);
}

function get_user_comment_popularity($user) {
    global $cid;
    $sql = "SELECT binlike,COUNT(binlike) FROM `comment` AS c INNER JOIN `comment_like` AS cl ON (c.id=cl.comment) WHERE c.user='$user' AND c.`active`=1 GROUP BY binlike";
    if ($result = mysql_query($sql,$cid)) {
        if (mysql_num_rows($result)) {
            $binlikes[0]=0; $binlikes[1]=0;
            while ($row = mysql_fetch_array($result))
                $binlikes[$row[0]] = $row[1];
            return array($binlikes[0],$binlikes[1]);
        }
        else
            return false;
    }
    else
        return false;
}

function get_current_academic_year() { // 09-10 returns 09
    $m = date('m');
    $y = date('y');
    if ($m < 9)
        return $y-1;
    else
        return $y;
}

function ordinal_suffix($value) {
    if (is_numeric($value)) {
        if(substr($value, -2, 2) == 11 || substr($value, -2, 2) == 12 || substr($value, -2, 2) == 13)
            $suffix = "th";
        else if (substr($value, -1, 1) == 1)
            $suffix = "st";
        else if (substr($value, -1, 1) == 2)
            $suffix = "nd";
        else if (substr($value, -1, 1) == 3)
            $suffix = "rd";
        else
            $suffix = "th";
        $suffix = "<sup>" . $suffix . "</sup>";
        return $value.$suffix;
    }
    else
        return false;
}

function guess_student_year($joinyear,$coursestr) {
    $yftpos = strpos($coursestr,'YFT'); // N Year Full Time course
    if ($yftpos === false)
        return false;
    if (($student_year = get_current_academic_year() - $joinyear) <= 5) // max 6 years
        if (is_numeric($coursestr[$yftpos-1]))
            if ($student_year < $coursestr[$yftpos-1])
                return $student_year+1;
    return false;
}

function get_yahoo_weather_code() {
    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $xml = curl_exec($ch);
        curl_close($ch);
        return $xml;
    }
    function format_result($input) {
        return strtolower(str_replace(array(' ', '(', ')'), array('-', '-', ''), $input));
    }
    function get_match($regex,$content) {
        preg_match($regex,$content,$matches);
        return $matches[1];
    }
    $data = get_data("http://weather.yahooapis.com/forecastrss?w=44418&u=c");
    $str = get_match('/<yweather:condition  text="(.*)" code="(.*)"/isU',$data);
    list($weather_class) = explode('"',substr($str,(strpos($str,'code="')+6)));
    return $weather_class;
}

function does_article_image_exist($article) {
    global $cid;
    $sql = "SELECT id FROM `article` WHERE id='$article' AND img1 IS NOT NULL";
    $rsc = mysql_query($sql,$cid);
    if (mysql_num_rows($rsc)) {
        $result = mysql_result($rsc,0);
        if ($result == 183 || $result == 742) {
            return false;
        } else {
            return $result;
        }
    } else {
        return false;
    }
}

function does_article_text_exist($article,$text) {
    global $cid;
    $sql = "SELECT id FROM `article` WHERE id='$article' AND text$text IS NOT NULL";
    return (mysql_num_rows(mysql_query($sql,$cid)));
}

function convert_smart_quotes($string) {
    $search = array(chr(145),
                    chr(146),
                    chr(147),
                    chr(148),
                    chr(151),
                    chr(133));
    $replace = array('&lsquo;',
                 '&rsquo;',
                 '&ldquo;',
                 '&rdquo;',
                 '&mdash;',
                 '&hellip;');
    return str_replace($search, $replace, $string);
}

function get_category_label($category) {
    global $cid;
    if (is_numeric($category)) {
        $rsc = mysql_query("SELECT label FROM `category` WHERE id=$category",$cid);
        if ($rsc)
            return mysql_result($rsc,0);
        else
            return false;
    }
    else
        return false;
}

function get_category_cat($category) {
    global $cid;
    if (is_numeric($category)) {
        $rsc = mysql_query("SELECT cat FROM `category` WHERE id=$category",$cid);
        if ($rsc)
            return mysql_result($rsc,0);
        else
            return false;
    }
    else
        return false;
}

function get_category_topstories($category) {
    global $cid;
    if (is_numeric($category))
        return mysql_fetch_array(mysql_query("SELECT top_slider_1,top_slider_2,top_slider_3,top_slider_4 FROM `category` WHERE id=$category",$cid));
    else
        return false;
}

function get_category_label_by_cat($cat) {
    global $cid;
    $rsc = mysql_query("SELECT label FROM `category` WHERE cat='$cat'",$cid);
    if (mysql_num_rows($rsc))
        return mysql_result($rsc,0);
    else
        return false;
}

function get_category_email_by_cat($cat) {
    global $cid;
    $rsc = mysql_query("SELECT email FROM `category` WHERE cat='$cat'",$cid);
    if ($rsc)
        return mysql_result($rsc,0);
    else
        return false;
}

function get_category_id_by_cat($cat) {
    global $cid;
    $rsc = mysql_query("SELECT id FROM `category` WHERE cat='$cat'",$cid);
    if ($rsc)
        return mysql_result($rsc,0);
    else
        return false;
}

function get_category_editors_by_cat($cat) {
    global $cid;
    $users = array();
    $sql = "SELECT user from `category_author` WHERE category='$cat' AND admin=1";
    $rsc = mysql_query($sql,$cid);
    $num = mysql_num_rows($rsc);
    //$rsc = mysql_query("SELECT editor1, editor2, editor3, editor4 FROM `category` WHERE cat='$cat'",$cid);
    if ($num) {
        //return array_unique(array_filter(mysql_fetch_array($rsc)));
        while (list($user) = mysql_fetch_array($rsc)) {
            $users[] = $user;
        }
        return $users;
    } else
        return false;
}

function output_in_english($array) {
    // sanity check
    if (!$array || !count ($array))
        return '';

    // get last element
    $last = array_pop ($array);

    // if it was the only element - return it
    if (!count ($array))
        return $last;

    return implode (', ', $array).'</b> and <b>'.$last;
}

function output_in_english_authors($array) {

    // sanity check
    if (!$array || !count ($array))
        return '';

    // change array into linked usernames
    foreach ($array as $key => $value) {
        $full_array[$key] = '<a href="user/'.$value.'/">'.get_vname_by_uname_db($value).'</a>';
    }

    // get last element
    $last = array_pop ($full_array);

    // if it was the only element - return it
    if (!count ($full_array))
        return $last;

    return implode (', ', $full_array).' and '.$last;

    //$output = '<a href="user/'.$array[0].'/">'.get_vname_by_uname_db($array[0]).'</a>';

    //if ($number > 1) {
        //for($i=1;$i<($number-1);$i++)
            //$output .= ', <a href="user/'.$array[$i].'/">'.get_vname_by_uname_db($array[$i]).'</a>';
    //}

    //if($number > 1)
        //$output .= ' and <a href="user/'.$array[$number-1].'/">'.get_vname_by_uname_db($array[$number-1]).'</a>';

    //return $output;
}

function get_twitter_text() {
    global $cid;
    return mysql_result(mysql_query("SELECT value FROM `text_global` WHERE `key`='undertwitter' LIMIT 1"),0);
}

function get_authorised_categories($uname,$admin=false) { // admin returns only categories for which uname is section editor (false only author)
    global $cid;
    if (($role = get_user_role($uname)) >= 30) { // senior editors are authorised for all categories
        $sql = "SELECT id FROM `category` WHERE id>0 AND active=1 ORDER BY label ASC";
        $rsc = mysql_query($sql,$cid);
        $authorised_categories = array();
        while ($row = mysql_fetch_array($rsc))
            $authorised_categories[] = $row[0];
        return $authorised_categories;
    }
    elseif ($role >= 10) {
        $sql = "SELECT category_author.category FROM `category_author` INNER JOIN `category` ON (category_author.category=category.id) WHERE category_author.user='$uname'".(($admin)?' AND admin=1':'')." ORDER BY label ASC";
        $rsc = mysql_query($sql,$cid);
        if (mysql_num_rows($rsc)) {
            $authorised_categories = array();
            while ($row = mysql_fetch_array($rsc))
                $authorised_categories[] = $row[0];
            return $authorised_categories;
        }
        else
            return array();
    }
    else
        return array();
}

function get_all_categories() {
    global $cid;
    $sql = "SELECT id FROM `category` WHERE id>0 AND active=1 ORDER BY label ASC";
    $rsc = mysql_query($sql,$cid);
    $categories = array();
    while ($row = mysql_fetch_array($rsc))
        $categories[] = $row[0];
    return $categories;
}

function get_available_article_images($uname) {
    if ((get_user_role($uname)) >= 10) {
        global $cid;
        $sql = "SELECT * FROM (SELECT id FROM `image` ORDER BY timestamp DESC LIMIT 200) AS t UNION SELECT id FROM `image` WHERE id=".DEFAULT_ARTICLE_IMG_ID;
        $rsc = mysql_query($sql,$cid);
        if (mysql_num_rows($rsc)) {
            $available_images = array();
            while ($row = mysql_fetch_array($rsc))
                $available_images[] = $row[0];
            return $available_images;
        }
        else
            return array();
    }
    else
        return array();
}

function get_available_article_texts($uname) {
    if ((get_user_role($uname)) >= 10) {
        global $cid;
        $sql = "SELECT id FROM `text_story` WHERE user='$uname' ORDER BY timestamp DESC LIMIT 50";
        $rsc = mysql_query($sql,$cid);
        if (mysql_num_rows($rsc)) {
            $available_texts = array();
            while ($row = mysql_fetch_array($rsc))
                $available_texts[] = $row[0];
            return $available_texts;
        }
        else
            return array();
    }
    else
        return array();
}

function get_popular_articles() { // limited
    global $cid;
    //$sql = "SELECT c.article,COUNT(*) AS count FROM `comment` AS c INNER JOIN `article` AS a ON (c.article=a.id) WHERE timestamp>(DATE_SUB(NOW(),INTERVAL ".MOST_POPULAR_INTERVAL." day)) AND a.published<NOW() GROUP BY article ORDER BY count DESC LIMIT ".POPULAR_ARTICLES; // prioritise articles with most comments
    // $sql = "SELECT c.article,COUNT(*) AS count FROM `comment` AS c INNER JOIN `article` AS a ON (c.article=a.id) WHERE c.`active`=1 AND timestamp>(DATE_SUB(NOW(),INTERVAL ".MOST_POPULAR_INTERVAL." day)) AND a.published<NOW() GROUP BY article ORDER BY c.timestamp DESC LIMIT ".POPULAR_ARTICLES; // go for most recent comments instead
    $sql = "SELECT article,SUM(count) AS count FROM ((SELECT c.article,COUNT(*) AS count FROM `comment` AS c INNER JOIN `article` AS a ON (c.article=a.id) WHERE c.`active`=1 AND timestamp>(DATE_SUB(NOW(),INTERVAL ".MOST_POPULAR_INTERVAL." day)) AND a.published<NOW() GROUP BY article ORDER BY timestamp DESC LIMIT 20) UNION ALL (SELECT ce.article,COUNT(*) AS count FROM `comment_ext` AS ce INNER JOIN `article` AS a ON (ce.article=a.id) WHERE ce.`active`=1 AND pending=0 AND timestamp>(DATE_SUB(NOW(),INTERVAL ".MOST_POPULAR_INTERVAL." day)) AND a.published<NOW() GROUP BY article ORDER BY timestamp DESC)) AS t GROUP BY article ORDER BY count DESC, article DESC LIMIT ".POPULAR_ARTICLES; // go for most recent comments instead
    $rsc = mysql_query($sql,$cid);
    if (mysql_num_rows($rsc)) {
        $popular_articles = array();
        while ($row = mysql_fetch_array($rsc))
            $popular_articles[] = $row[0];
        return $popular_articles;
    }
    else
        return array();
}

function check_section_admin($uname,$category) {
    global $cid;
    if (get_user_role($uname) >= 30) // senior editor +
        return true;
    else {
        $sql = "SELECT COUNT(*) FROM `category_author` WHERE category=$category AND user='$uname' AND admin=1";
        return (mysql_result(mysql_query($sql,$cid),0));
    }
}

function insert_article($title,$short_title,$teaser,$author,$category,$text1,$img1,$text2=false,$img2=false,$img2lr=false) {
    global $cid;
    $sql = "INSERT INTO `article` (title,short_title,teaser,author,category,text1,img1,text2,img2,img2lr) ".
        "VALUES ('$title','$short_title','$teaser','$author',$category,$text1,$img1,".(($text2)?"$text2":'NULL').','.(($img2)?"$img2,$img2lr":"NULL,NULL").")";
    return (mysql_query($sql,$cid));
}

function publish_article($id,$uname,$time='NOW()') {
    if (check_article($id)) {
        global $cid;
        return (mysql_query("UPDATE `article` SET published=NOW(),approvedby='$uname',published=$time WHERE id=$id LIMIT 1",$cid));
    }
    else
        return false;
}

function unpublish_article($id) {
    if (check_article($id)) {
        global $cid;
        return (mysql_query("UPDATE `article` SET published=NULL,approvedby=NULL WHERE id=$id LIMIT 1",$cid));
    }
    else
        return false;
}

function get_number_articles_to_publish($uname) {
    global $cid;
    if (get_user_role($uname) == 100) { // super user so get all articles to publish
        $sql = "SELECT COUNT(*) FROM `article` WHERE published = NULL";
        $number = mysql_result(mysql_query($sql,$cid),0,0);
    }
    return $number;
}

function get_number_articles_to_publish2() {
    global $cid;
    $sql = "SELECT COUNT(*) FROM `article` WHERE published IS NULL AND hidden = 0";
    $number = mysql_result(mysql_query($sql,$cid),0,0);
    return $number;
}

function get_img2lr($article) {
    global $cid;
    if (check_article($article))
        return mysql_result(mysql_query("SELECT img2lr FROM `article` WHERE id=$article",$cid),0);
    else
        return false;
}

function get_recent_articles() {
    global $cid;
    $sql = "SELECT id FROM `article` WHERE published IS NOT NULL ORDER BY published DESC LIMIT 50";
    $rsc = mysql_query($sql,$cid);
    if (mysql_num_rows($rsc)) {
        $articles = array();
        while (list($article) = mysql_fetch_array($rsc))
            $articles[] = $article;
        return $articles;
    }
    else
        return array();
}

function get_recent_category_articles($cat) {
    global $cid;
    $sql = "SELECT id FROM `article` WHERE category=$cat ORDER BY published DESC LIMIT 50";
    $rsc = mysql_query($sql,$cid);
    $articles = array();
    while (list($article) = mysql_fetch_array($rsc))
        $articles[] = $article;
    return $articles;
}

function get_authors() {
    global $cid;
    $sql = "SELECT `user`,`name` FROM `user` WHERE role>=10 ORDER BY name ASC";
    $rsc = mysql_query($sql,$cid);
    $authors = array();
    $i = 0;
    while ($row = mysql_fetch_array($rsc)) {
        $authors[] = $row;
        $i++;
    }
    return $authors;
}

function clean_text($text) {
    $search = array(
        '&',
        '<',
        '>',
        '"',
        chr(212),
        chr(213),
        chr(210),
        chr(211),
        chr(209),
        chr(208),
        chr(201),
        chr(145),
        chr(146),
        chr(147),
        chr(148),
        chr(151),
        chr(150),
        chr(133)
    );
    $replace = array(
        '&amp;',
        '&lt;',
        '&gt;',
        '&quot;',
        '&8216;',
        '&8217;',
        '&8220;',
        '&8221;',
        '&8211;',
        '&8212;',
        '&8230;',
        '&8216;',
        '&8217;',
        '&8220;',
        '&8221;',
        '&8211;',
        '&8212;',
        '&8230;'
    );
    return str_replace($search,$replace,$text);
}

function unclean_text($text) {
    $replace = array(
        '&',
        '<',
        '>',
        '"',
        chr(212),
        "'",
        "'",
        "'",
        //chr(210),
        //chr(211),
        chr(209),
        '-',
        chr(201),
        chr(145),
        chr(146),
        chr(147),
        chr(148),
        chr(151),
        chr(150),
        chr(133),
        "'",
        "'"
    );
    $search = array(
        '&amp;',
        '&lt;',
        '&gt;',
        '&quot;',
        '&8216;',
        '&8217;',
        '&8220;',
        '&8221;',
        '&8211;',
        '&8212;',
        '&8230;',
        '&8216;',
        '&8217;',
        '&8220;',
        '&8221;',
        '&8211;',
        '&8212;',
        '&8230;',
        '&rsquo;',
        '&lsquo;'
    );
    return str_replace($search,$replace,$text);
}

function article_delete($id) {
    // foreign keys cascade
    if (check_article($id)) {
        global $cid;
        $sql1 = "DELETE FROM article_visit WHERE article=$id";
        $sql0 = "DELETE FROM `article` WHERE id=$id LIMIT 1";
        if (mysql_query($sql1,$cid) && mysql_query($sql0,$cid))
            return true;
        else {
            echo mysql_error();
            return false;
        }
    }
}

function article_delete2($id) {
    // foreign keys cascade
    if (check_article2($id)) {
        global $cid;
        $sql1 = "DELETE FROM article_visit WHERE article=$id";
        $sql0 = "DELETE FROM `article` WHERE id=$id LIMIT 1";
        $sql2 = "DELETE FROM `article_author` WHERE article=$id";
        $sql3 = "DELETE FROM `article_topic` WHERE article_id=$id";
        if (mysql_query($sql1,$cid) && mysql_query($sql2,$cid) && mysql_query($sql0,$cid))
            return true;
        else {
            echo mysql_error();
            return false;
        }
    }
}

function get_online_users() {
    global $cid;
    $sql = "SELECT DISTINCT user FROM `login` WHERE timestamp > (DATE_SUB(NOW(),INTERVAL ".ONLINE_USERS_INTERVAL.")) UNION SELECT DISTINCT user FROM `comment` WHERE timestamp > (DATE_SUB(NOW(),INTERVAL ".ONLINE_USERS_INTERVAL."))";
    $rsc = mysql_query($sql,$cid);
    if (mysql_num_rows($rsc)) {
        $users = array();
        while (list($user) = mysql_fetch_array($rsc))
            $users[] = '<li><a href="/?user='.$user.'">'.get_vname_by_uname_db($user).'</a></li>';
        return $users;
    }
    else
        return array();
}

function get_correct_utf8_mysql_string($s) {
    if(empty($s))
        return $s;
    $s = preg_match_all("#[\x09\x0A\x0D\x20-\x7E]|
        [\xC2-\xDF][\x80-\xBF]|
        \xE0[\xA0-\xBF][\x80-\xBF]|
        [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|
        \xED[\x80-\x9F][\x80-\xBF]#x", $s, $m );
    return implode("",$m[0]);
}

function log_page_visit($article) {
    global $cid;
    $sql = "INSERT INTO article_visit (article,user,IP,referrer) VALUES ('$article',";
    $sql .= ($u = is_logged_in()) ? "'$u'" : "NULL";
    $sql .= ",'".$_SERVER['REMOTE_ADDR']."'";
    $sql .= ",'".$_SERVER['HTTP_REFERER']."'";
    $sql .= ")";
    if (!mysql_query($sql,$cid))
        die("Error: ".mysql_error());
}

function get_mostviewed_articles() {
    global $cid;
    $sql = "SELECT DISTINCT article,COUNT(article) AS c FROM (SELECT article FROM article_visit AS av INNER JOIN article AS a ON (av.article=a.id) WHERE a.published IS NOT NULL ORDER BY timestamp DESC LIMIT 500) AS t GROUP BY article ORDER BY c DESC LIMIT 5";
    $rsc = mysql_query($sql,$cid);
    while ($row = mysql_fetch_array($rsc))
        $viewed_articles[] = $row[0];
    return $viewed_articles;
}

function clean_content2($text) {
    $result = strip_tags($text, '<p><a><div><b><i><br><blockquote><object><param><embed><li><ul><ol><strong><img><h1><h2><h3><h4><h5><h6><em><iframe><strike>'); // Gets rid of html tags except <p><a><div>
    $result = preg_replace('#<div[^>]*(?:/>|>(?:\s|&nbsp;)*</div>)#im', '', $result); // Removes empty html div tags
    $result = preg_replace('#<span*(?:/>|>(?:\s|&nbsp;)[^>]*</span>)#im', '', $result); // Removes empty html div tags
    $result = preg_replace('#<p[^>]*(?:/>|>(?:\s|&nbsp;)*</p>)#im', '', $result); // Removes empty html p tags
    //$result = preg_replace("/<(\/)*div[^>]*>/", "<\\1p>", $result); // Changes div tags into <p> tags
    return $result;
}

function article_url($article) {
    $cat = get_article_category_cat($article);
    $title = get_article_title($article);

    $title = strtolower($title); // Make title lowercase
    $title= preg_replace('/[^\w\d_ -]/si', '', $title); // Remove special characters
    $dashed = str_replace( " ", "-", $title); // Replace spaces with hypens

    $output = $cat.'/'.$article.'/'.$dashed.'/';
    return $output;
}

function full_article_url($article) {
    $output = STANDARD_URL;
    $output .= article_url($article);
    return $output;
}

function urlise_text($string) {

    $title = strtolower($string); // Make title lowercase
    $title= preg_replace('/[^\w\d_ -]/si', '', $title); // Remove special characters
    $dashed = str_replace( " ", "-", $title); // Replace spaces with hypens

    return $dashed;
}

function get_user_pic($uname) {
    global $cid;
    $sql = "SELECT img FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_user_email($uname) {
    global $cid;
    $sql = "SELECT email FROM `user` WHERE user='$uname'";
    $result = mysql_result(mysql_query($sql,$cid),0);
    if(!$result)
        return false;
        //return get_vname_by_uname_db($uname);
    else
        return $result;
}

function get_user_email_show($uname) {
    global $cid;
    $sql = "SELECT show_email FROM `user` WHERE user='$uname'";
    $result = mysql_result(mysql_query($sql,$cid),0);
    if(!$result)
        return false;
        //return get_vname_by_uname_db($uname);
    else
        return $result;
}

function get_user_email_full($uname) {
    global $cid;
    $sql = "SELECT email FROM `user` WHERE user='$uname'";
    $result = mysql_result(mysql_query($sql,$cid),0);
    if(!$result)
        return get_vname_by_uname_db($uname);
    else
        return $result;
}

function get_user_description($uname) {
    global $cid;
    $sql = "SELECT description FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_user_facebook($uname) {
    global $cid;
    $sql = "SELECT facebook FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_user_twitter($uname) {
    global $cid;
    $sql = "SELECT twitter FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_user_website($uname) {
    global $cid;
    $sql = "SELECT websiteurl FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_user_website_name($uname) {
    global $cid;
    $sql = "SELECT websitename FROM `user` WHERE user='$uname'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function hide_email($email) {
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set); $cipher_text = '';
    $id = 'e'.rand(1,999999999);
    for ($i=0;$i<strlen($email);$i+=1)
        $cipher_text.= $key[strpos($character_set,$email[$i])];
    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
    return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
}

function hide_email_full($email, $name) {
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set); $cipher_text = '';
    $id = 'e'.rand(1,999999999);
    for ($i=0;$i<strlen($email);$i+=1)
        $cipher_text.= $key[strpos($character_set,$email[$i])];
    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";var n="'.$name.'";';
    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+n+"</a>"';
    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
    return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
}

function trim_text($string, $limit) {
    $string = strip_tags($string);

    if(strlen($string) <= $limit) { // Do nothing
    }
    else $string = substr($string, 0, $limit).' ... ';

    return $string;
}

function get_likes($comment) {
    global $cid;
    $sql = "SELECT binlike FROM `comment_like` WHERE comment=$comment";
    $result = mysql_query($sql,$cid);
    $likes = 0;
    if (mysql_num_rows($result)) {
        while($row = mysql_fetch_array($result)){
            if ($row['binlike'] == 1)
                $likes++;
        }
        return $likes;
    } else {
        return 0;
    }
}

function get_dislikes($comment) {
    global $cid;
    $sql = "SELECT id,binlike FROM `comment_like` WHERE comment=$comment";
    $result = mysql_query($sql,$cid);
    $dislikes = 0;
    if (mysql_num_rows($result)) {
        while($row = mysql_fetch_array($result)){
            if ($row['binlike'] == 0)
                $dislikes++;
        }
        return $dislikes;
    } else {
        return 0;
    }
}

function user_like_comment($comment, $user) { // check if user has liked/disliked comment already
    global $cid;
    $sql = "SELECT COUNT(*) FROM `comment_like` WHERE user='$user' AND comment=$comment";
    return (mysql_result(mysql_query($sql,$cid),0));
}

function get_albumName_from_id($albumID) {
    $sql = "SELECT albumName FROM `thephig_albums` WHERE albumID=".$albumID;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row[0];
}

function get_folderName_from_id($albumID) {
    $sql = "SELECT albumFolder FROM `thephig_albums` WHERE albumID=".$albumID;
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    return $row[0];
}

function check_if_section_editor($uname, $article) {
    $cat = get_article_category_id($article);
    $sql = "SELECT COUNT(*) FROM `category_author` WHERE user='$uname' AND admin='1' AND category='$cat'";
    $result = mysql_query($sql);
    if($result)
        return mysql_result($result, 0);
    else
        return false;
}

function get_section_from_user($uname) {
    global $cid;
    $sql = "SELECT category FROM `category_author` WHERE user='$uname' AND admin='1'";
    $result = mysql_query($sql, $cid);
    if(mysql_num_rows($result))
        return mysql_result($result, 0);
    else
        return false;
    /*$category = false;

    while($row = mysql_fetch_array($result)) {
        if (in_array($uname, $row))
            $check = $row['id'];
    }

    return $check;*/
}

function check_if_draft_article($id) {
    global $cid;
    $sql = "SELECT hidden FROM `article` WHERE id=$id";
    return (mysql_result(mysql_query($sql,$cid),0) == 1);
}

function user_role_long($uname) {
    $role = get_user_role($uname);
    $cat = get_section_from_user($uname);
    if ($role == 0)
        return "You are a user";
    else if ($role <= 10)
        return "You are an author";
    else if ($role <= 20) {
        if ($cat)
            return "You are a section editor of ".get_category_label($cat);
        else
            return "You are a section editor but not of any section! Poor you :(";

    } else if ($role <= 30)
        return "You are a senior editor";
    else
        return "You are a super user. Get you!";

}

function plural($num) {
    if ($num != 1)
        return "s";
}

function getRelativeTime($date) {
    $diff = time() - $date; //strtotime($date);
    if ($diff<60)
        return $diff . " second" . plural($diff) . " ago";
    $diff = round($diff/60);
    if ($diff<60)
        return $diff . " minute" . plural($diff) . " ago";
    $diff = round($diff/60);
    if ($diff<24)
        return $diff . " hour" . plural($diff) . " ago";
    $diff = round($diff/24);
    if ($diff<7)
        return $diff . " day" . plural($diff) . " ago";
    $diff = round($diff/7);
    if ($diff<4)
        return $diff . " week" . plural($diff) . " ago";
    return "on " . date("F j, Y", $date);
}

function trunc_text($string, $wordlimit = 100){
    $words = explode(" ",$string);
    //if(count($words) > $wordlimit) {
      //$append = ' ... <br/><a href="'.article_url($id).'" title="Read more" id="readmorelink">Read more</a>';
    //}
    return implode(" ",array_splice($words,0,$wordlimit));
}


function get_google_weather() {
    $requestAddress = "http://www.google.com/ig/api?weather=SW72BB&hl=en";
    // Downloads weather data based on location - I used my zip code.
    $xml_str = file_get_contents($requestAddress,0);
    // Parses XML
    $xml = new SimplexmlElement($xml_str);
    // Loops XML
    $count = 0;
    echo '<div id="weather">';

    foreach($xml->weather as $item) {
        foreach($item->forecast_conditions as $new) {
            echo '<div class="weatherIcon">';
            echo '<img src="http://www.google.com/' .$new->icon['data'] . '"/><br/>';
            echo $new->day_of_week['data'];
            echo '</div>';
        }
    }

    echo '</div>';
}

/* ------------------------------------------------------ */
/* Media admin */
/* ------------------------------------------------------ */

function get_album_name($id) {
    global $cid;
    $sql = "SELECT albumName FROM `media_photo_albums` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_album_desc($id) {
    global $cid;
    $sql = "SELECT albumDesc FROM `media_photo_albums` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_album_author($id) {
    global $cid;
    $sql = "SELECT albumAuthor FROM `media_photo_albums` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_album_image($id) {
    global $cid;
    $sql = "SELECT albumThumb FROM `media_photo_albums` WHERE id=$id";
    $img = mysql_result(mysql_query($sql,$cid),0);

    $sql = "SELECT imageName FROM `media_photo_images` WHERE albumID=$id AND id='$img'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function album_is_published($id) {
    global $cid;
    $sql = "SELECT visible FROM `media_photo_albums` WHERE id=$id";
    $visible = mysql_result(mysql_query($sql,$cid),0);

    if($visible)
        return true;
    else
        return false;
}

function get_image_name($id) {
    global $cid;

    $sql = "SELECT imageName FROM `media_photo_images` WHERE id='$id'";
    return mysql_result(mysql_query($sql,$cid),0);
}

function number_of_album_pics($id) {
    global $cid;
    $sql = "SELECT * FROM `media_photo_images` WHERE albumID=$id";
    $result = mysql_query($sql) or die(mysql_error());
    return mysql_num_rows($result);
}

/* ------------------------------------------------------ */
/* Video admin */
/* ------------------------------------------------------ */

function get_video_name($id) {
    global $cid;
    $sql = "SELECT title FROM `media_video` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_video_id($id) {
    global $cid;
    $sql = "SELECT video_id FROM `media_video` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_video_date($id) {
    global $cid;
    $sql = "SELECT date FROM `media_video` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_video_desc($id) {
    global $cid;
    $sql = "SELECT description FROM `media_video` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

function get_video_site($id) {
    global $cid;
    $sql = "SELECT site FROM `media_video` WHERE id=$id";
    return mysql_result(mysql_query($sql,$cid),0);
}

/**
 *
 * @create a roman numeral from a number
 *
 * @param int $num
 *
 * @return string
 *
 */
function romanNumerals($num) {
    $n = intval($num);
    $res = '';

    /*** roman_numerals array  ***/
    $roman_numerals = array(
                'M'  => 1000,
                'CM' => 900,
                'D'  => 500,
                'CD' => 400,
                'C'  => 100,
                'XC' => 90,
                'L'  => 50,
                'XL' => 40,
                'X'  => 10,
                'IX' => 9,
                'V'  => 5,
                'IV' => 4,
                'I'  => 1);

    foreach ($roman_numerals as $roman => $number)
    {
        /*** divide to get  matches ***/
        $matches = intval($n / $number);

        /*** assign the roman char * $matches ***/
        $res .= str_repeat($roman, $matches);

        /*** substract from the number ***/
        $n = $n % $number;
    }

    /*** return the res ***/
    return $res;
}

function article_optimised($article) {
    global $cid;
    $sql = "SELECT optimised FROM `optimise` WHERE article='$article' AND optimised = 1";
    if(mysql_num_rows(mysql_query($sql,$cid)))
        return true;
    else
        return false;
}

function addhttp($url) {
    $url = trim($url);
    if($url != '' && $url != 'http://') {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
    } else {
        $url = '';
    }
    return $url;
}

# Original PHP code by Chirp Internet: www.chirp.com.au
  # Please acknowledge use of this code by including this header.

function myWrap($input, $chars, $lines = false) {
    # the simple case - return wrapped words
    if(!$lines) return wordwrap($input, $chars, "\n");

    # truncate to maximum possible number of characters
    $retval = substr($input, 0, $chars * $lines);

    # apply wrapping and return first $lines lines
    $retval = wordwrap($retval, $chars, "\n");
    preg_match("/(.+\n?){0,$lines}/", $retval, $regs);
    return $regs[0];
}

function get_section_twitter($cat) {
    global $cid;
    $sql = "SELECT twitter FROM `category` WHERE cat='$cat'";
    $result = mysql_query($sql,$cid);
    if ($result) {
        return mysql_result($result, 0);
    } else {
        return false;
    }
}

function get_comments_to_approve() {
    global $cid;
    $sql = "SELECT ce.*,a.title FROM comment_ext AS ce INNER JOIN article AS a ON (ce.article=a.id) WHERE ACTIVE=1 AND pending=1 ORDER BY timestamp ASC";
    return mysql_num_rows(mysql_query($sql));
}

function shortcodes ($input) {
    global $gallerypage;

    // only works with photos at the moment
    $tagOne = "[photo id=";
    $tagTwo = "]";

    $startTagPos = strrpos($input, $tagOne);
    $endTagPos = strrpos($input, $tagTwo);
    $tagLength = $endTagPos - $startTagPos + 1;

    if($startTagPos) {
        $idLength = $endTagPos - $startTagPos - strlen($tagOne);
        $id = substr($input, ($startTagPos+strlen($tagOne)), $idLength);

        // get photo gallery based on id
        $sql = "SELECT * FROM `media_photo_images` WHERE albumID='".$id."' ORDER BY id ASC";
        $result = mysql_query($sql);
        $replacement = '<div id="photogallery"><ul>';
        while($row = mysql_fetch_array($result)){
            $replacement .= '<img src="/gallery/gallery_images/timthumb.php?src=/gallery/gallery_images/images/'.$row['imageName'].'&h=310px&zc=0" title="'.$row['imageTitle'].'" height="310" alt="'.$row['imageTitle'].'"/>';
        }
        $replacement .= '</div>';

        $gallerypage = $id;

        //return substr_replace($input, $replacement, $startTagPos, $tagLength);
        return substr_replace($input, '', $startTagPos, $tagLength);
    } else {
        return $input;
    }
}

function sbfeedback () {
    if(ldap_get_name($_POST['username'])) {
        $vname = ldap_get_name($_POST['username']);
        // multiple recipients
        $to  = 'felix@imperial.ac.uk';

        // subject
        $subject = '[summerball] '.$vname.' left some feedback';

        // message
        $message = '
          <p><b>'.$vname.' ('.$_POST['username'].') has left some feedback on the summer ball</b></p>
          <p><b>Did you go to the summer ball:</b> '.$_POST['didyou'].
          '<p><b>Comment:</b></p>
          <p>'.$_POST['comment'].'</p>
          <p><b>Keep my name anonymous: </b>
        ';

        if($_POST['anon'] == 'on') {
            $message .= 'Yes';
        } else {
            $message .= 'No';
        }

        $message .= '</p><p>xxx<p>';

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        // Additional headers
        $headers .= "From: ".EMAIL_FROM_ADDR."\r\n" .
        'Reply-To: '.EMAIL_REPLYTO_ADDR."\r\n" .
        'X-Mailer: PHP/' . phpversion();

        // Mail it
        mail($to, $subject, $message, $headers);

        header("Location: ".STANDARD_URL."summerball/?success=true"); /* Redirect browser */
        /* Make sure that code below does not get executed when we redirect. */
        exit;
    } else {
        return false;
    }
}
?>
