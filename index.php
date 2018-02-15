<?php
/*
 * Kianna Dyck
 * Jen Shin
 */

// Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require the autoload file
require_once('vendor/autoload.php');

// Create an instance of the Base class
$f3 = Base::instance();

// Set debug level 0 = off, 3 = max level
$f3->set('DEBUG', 3);

// Define a default route
$f3->route('GET /', function($f3) {
    $f3->set('options', array(
            'deck1',
            'deck2',
            'deck3')
    );

    $template = new Template();
    echo $template->render('views/home.html');
});

// Run fat free
$f3->run();