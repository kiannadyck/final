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

//temp
require_once('models/db-functions.php');

// Start a session
session_start();

// Create an instance of the Base class
$f3 = Base::instance();

// Connect to the database (temp)
$dbh = connect();

// Set debug level 0 = off, 3 = max level
$f3->set('DEBUG', 3);

// Define a default route
$f3->route('GET|POST /', function($f3) {

    // Define array of decks (stored in database)
    $f3->set('options', array(
            'deck1',
            'deck2',
            'deck3')
    );

    //    print_r($_POST);

    /*Array (
        [deckOption] => deck2
        [choice] => play
        [submit] => Submit )*/

    if(isset($_POST['submit']))
    {
        // retrieve values from POST array
        $deck = $_POST['deckOption'];
        $choice = $_POST['choice'];

        // validate data

        // if data is valid, retrieve deck from database

        // store deck in session?

        // Reroute to next page depending on choice
        if ($choice == "play") {
            $f3->reroute('/play');
        } else if ($choice == "edit") {
            $f3->reroute('/edit');
        }

    }



    $template = new Template();
    echo $template->render('views/home.html');
});

// Define a route for playing/viewing flashcards
$f3->route('GET /play', function($f3) {

    $template = new Template();
    echo $template->render('views/view-flashcards.html');
});

// Define a route for editing flashcards from an existing deck
$f3->route('GET /edit', function($f3) {

    $template = new Template();
    echo $template->render('views/edit-flashcards.html');
});

// Define a route for creating a new deck
$f3->route('GET /create', function($f3) {

    $template = new Template();
    echo $template->render('views/create-new-deck.html');
});

// Define route for login
$f3->route('GET /login', function($f3) {

    $template = new Template();
    echo $template->render('views/login.html');
});

// Define route for registration of new users
$f3->route('GET /register', function($f3) {

    $template = new Template();
    echo $template->render('views/register.html');
});

// Run fat free
$f3->run();