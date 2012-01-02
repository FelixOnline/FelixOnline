<?php
/*
 * Felix Online
 */
 
// set up Felix Online environment
require_once('bootstrap.php');

$currentuser = new CurrentUser();

/*
 * Routes
 */
$urls = array(
    '/' => 'FrontpageController',
    '/user/(?P<user>[a-zA-Z0-9_-]+)' => 'UserController',
    '/user/(?P<user>[a-zA-Z0-9_-]+)/(?P<page>[0-9]+)' => 'UserController',
    '/media/(?P<type>[a-zA-Z0-9_-]+)' => 'MediaController',
    '/media/(?P<type>[a-zA-Z0-9_-]+)/(?P<id>[0-9]+)/.*' => 'MediaController',
    '/search' => 'SearchController',
    '/(?P<cat>[a-zA-Z]+)' => 'CategoryController',
    '/(?P<cat>[a-zA-Z]+)/(?P<page>[0-9]+)' => 'CategoryController',
    '/(?P<cat>[a-zA-Z]+)/(?P<id>[0-9]+)/(?P<title>[a-zA-Z0-9_-]+)/.*' => 'ArticleController'
);

try { // try mapping request to urls
    glue::stick($urls);
} catch (Exception $e) { // if it fails then send a 404 response
    $theme->render('404');
}

    /*
RewriteRule ^issuearchive/issue/([0-9]+)/$ ?issuearchive=true&issue=$1 [QSA]
RewriteRule ^issuearchive/decade/([0-9]+)/$ ?issuearchive=true&d=$1 [QSA]
RewriteRule ^issuearchive/year/([0-9]+)/$ ?issuearchive=true&y=$1 [QSA]
RewriteRule ^issuearchive/$ ?issuearchive=true [QSA]
RewriteRule ^media/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/.*$ ?media=$1&name=$2 [QSA]
RewriteRule ^media/photo/$ ?media=photo [QSA]
RewriteRule ^media/video/$ ?media=video [QSA]
RewriteRule ^media/radio/$ ?media=radio [QSA]
RewriteRule ^media/.*$ ?media=true [QSA]
RewriteRule ^media/$ ?media=true [QSA]
RewriteRule ^search/$ ?search=true [QSA]
RewriteRule ^publications/$ ?publications=true [QSA]
RewriteRule ^contact/$ ?contact=true [QSA]
RewriteRule ^phoenix/$ ?page=phoenix [QSA]
RewriteRule ^phoenix/([a-zA-Z0-9_-]+)/$ ?page=phoenix&subpage=$1 [QSA]
RewriteRule ^summerball/$ ?page=summerball [QSA]
RewriteRule ^([a-zA-Z0-9_-]+)/$ ?cat=$1 [QSA]
RewriteRule ^([a-zA-Z0-9_-]+)/([0-9]+)/$ ?cat=$1&p=$2 [QSA]
RewriteRule ^([a-zA-Z0-9_-]+)/([0-9]+)/([a-zA-Z0-9_-]+)/.*$ ?article=$2 [QSA]
     */

/*
    //require_once('mobiledetect.php');
    require_once('inc/common.inc.php');

    // comment submission
    require_once('inc/comment.php');

    global $sberror;
    $sberror = true;
    if(isset($_POST['sbsubmit'])){
        $sberror = sbfeedback();
    }

    include('header.php');

    if ($_GET['media']) {
        include_once('mediapage.php');
    } else if ($_GET['issuearchive']) {
        include_once('archive.php');
    } else if ($_GET['publications']) {
        include_once('publications.php');
    } else {
        include('navigation.php');
        //  Change display dependant on $_GET variable
        $get = array_shift(array_keys($_GET));
        switch ($get) {
            case "article":
                include_once('views/article/articleSingle.html.php');
                break;
            case "cat":
                include_once('section.php');
                break;
            case "id":
                include_once('users.php');
                break;
            case "media":
                include_once('media.php');
                break;
            case "search":
                include_once('search.php');
                break;
            case "contact":
                include_once('contact.php');
                break;
            case "page":
                include_once('pages/'.$_GET['page'].'.php');
                break;
            case "":
                include_once('frontpage.php');
                break;
            case "session":
                include_once('frontpage.php');
                break;
            default:
                include_once('404.php');
                break;
        }
    } // end of media page statement
    include('footer.php'); 
 */
?>
