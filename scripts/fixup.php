<?php
/**
 * Tidy common foreign key errors
 */

if(php_sapi_name() === 'cli') {
    die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../bootstrap.php';

$app = \FelixOnline\Core\App::getInstance();

// Delete authorships for missing authors
foreach(fetchall('SELECT DISTINCT author FROM article_author') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['author'].'"');

    if(count($count) == 0) {
        query("DELETE FROM article_author WHERE author = '".$user['author']."'");
    }
}

// Set null any visits relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM article_visit') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("UPDATE article_visit SET user = NULL WHERE user = '".$user['user']."'");
    }
}


// Assess whether the users exist from liveblog posts
foreach(fetchall('SELECT DISTINCT author FROM blog_post') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['author'].'"');

    if(count($count) == 0) {
        query("UPDATE blog_post SET author = 'felix' WHERE user = '".$user['author']."'");
    }
}

// Set null any comments relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM category_author') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("DELETE FROM category_author WHERE user = '".$user['user']."'");
    }
}

// Delete any comments with ID of 0
query("DELETE FROM comment WHERE article = 0");

// Set null any comments relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM comment') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("UPDATE comment SET user = NULL WHERE user = '".$user['user']."'");
    }
}

// Delete likes relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM comment_like') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("DELETE FROM comment_like WHERE user = '".$user['user']."'");
    }
}

// Delete likes relating to missing comments
foreach(fetchall('SELECT DISTINCT comment FROM comment_like') as $comment) {
    $count = fetchall('SELECT * FROM comment WHERE id = "'.$comment['comment'].'"');

    if(count($count) == 0) {
        query("DELETE FROM comment_like WHERE comment = '".$comment['comment']."'");
    }
}


// Delete cookies relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM cookies') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("DELETE FROM cookies WHERE user = '".$user['user']."'");
    }
}


// Set to felix any images relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM image') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("UPDATE image SET user = 'felix' WHERE user = '".$user['user']."'");
    }
}

// Delete cookies relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM login') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("DELETE FROM login WHERE user = '".$user['user']."'");
    }
}


// Set to felix any notices relating to missing users
foreach(fetchall('SELECT DISTINCT author FROM notices') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['author'].'"');

    if(count($count) == 0) {
        query("UPDATE notices SET author = 'felix' WHERE author = '".$user['author']."'");
    }
}


// Set to felix any polls relating to missing users
foreach(fetchall('SELECT DISTINCT author FROM polls') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['author'].'"');

    if(count($count) == 0) {
        query("UPDATE polls SET author = 'felix' WHERE author = '".$user['author']."'");
    }
}

// Set to felix any texts relating to missing users
foreach(fetchall('SELECT DISTINCT user FROM text_story') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("UPDATE text_story SET user = 'felix' WHERE user = '".$user['user']."'");
    }
}

// Set to felix any approvedby relating to missing users
foreach(fetchall('SELECT DISTINCT approvedby FROM article') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['approvedby'].'"');

    if(count($count) == 0) {
        query("UPDATE article SET approvedby = 'felix' WHERE approvedby = '".$user['approvedby']."'");
    }
}

// Legacy poll vote
foreach(fetchall('SELECT DISTINCT user FROM poll_vote') as $user) {
    $count = fetchall('SELECT * FROM user WHERE user = "'.$user['user'].'"');

    if(count($count) == 0) {
        query("DELETE FROM poll_vote WHERE user = '".$user['user']."'");
    }
}

function fetchall($sql) {
    $app = \FelixOnline\Core\App::getInstance();

	return $app['db']->get_results($sql, ARRAY_A);
}

function query($sql) {
    $app = \FelixOnline\Core\App::getInstance();

	return $app['db']->query($sql);
}

$app['cache']->flush();

echo "All done.\n";
