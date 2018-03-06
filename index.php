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
$f3->route('GET|POST /login', function($f3) {

    global $dbh; // temp?
    $email = "";
    $password = "";

    if(isset($_POST['submit'])) {
        // get post variables (username,password,password2)
        // Array ( [username] => Kianna [password] => 123 [password2] => 123 [submit] => Create Account )

        $isValid = true;

        if(!empty($_POST['username'])) {
            $email = $_POST['username']; // email
        } else {
            echo "Please enter a username.";
            $isValid = false;
        }

        if(!empty($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            echo "Please enter a password.";
            $isValid = false;
        }

        if($isValid)
        {
            // check if username and password match stored credentials

            // retrieve userid
            $result = getUser($email);
            if ($result == null) {
                echo $email." does not exist.";
            } else {
                // check password
                if ($result['password'] != sha1($password)) {
                    echo "<p> From Database: ".$result['password']."</p>";
                    echo sha1($password);

                    echo "Password does not match.";
                } else {
                    $_SESSION['userId'] = $result['userId'];
                    echo "<p> From Database: ".$result['password']."</p>";
                    echo sha1($password);
                }
            }

        }


    } // isset



    $template = new Template();
    echo $template->render('views/login.html');
});

// Define route for registration of new users
$f3->route('GET|POST /register', function($f3) {

    global $dbh; // temp?

    if(isset($_POST['submit'])) {
        // get post variables (username,password,password2)
        // Array ( [username] => Kianna [password] => 123 [password2] => 123 [submit] => Create Account )

        $email = $_POST['username']; // email
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $isValid = true;

        // validation (move to different page later)
        // validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo("$email is not a valid email address");
            $isValid = false;
        }

        // validate password
        if($password != $password2) {
            echo("Passwords do not match.");
            $isValid = false;
        }

        if($isValid)
        {
            // call function to addUser
            $success = addNewUser($email, $password);

            // if successful, get user id
            if($success)
            {
                $_SESSION['userId'] = $dbh->lastInsertId();
                echo "Account successfully created!";
            } else {
                echo "Username already in use.";
            }
        }

    }



    $template = new Template();
    echo $template->render('views/register.html');
});

// Run fat free
$f3->run();