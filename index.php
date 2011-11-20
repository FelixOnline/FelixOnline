<?php
    //require_once('mobiledetect.php');
    require_once('inc/common.inc.php');
    require_once('inc/authentication.php');
?>

<?php
    // comment submission
    require_once('inc/comment.php');

    global $sberror;
    $sberror = true;
    if(isset($_POST['sbsubmit'])){
        $sberror = sbfeedback();
    }
?>

<?php include('header.php'); ?>

<?php if ($_GET['media']) {
        include_once('media.php');
        } else if ($_GET['issuearchive']) {
            include_once('archive.php');
        } else if ($_GET['publications']) {
            include_once('publications.php');
        } else {
?>

<?php include('navigation.php'); ?>

<?php
    //  Change display dependant on $_GET variable
    $get = array_shift(array_keys($_GET));
    switch ($get) {
        case "article":
            include_once('page.php');
            break;
        case "cat":
            include_once('section.php');
            break;
        case "id":
            include_once('user.php');
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

?>

<?php } // end of media page statement?>

<?php include('footer.php'); ?>
