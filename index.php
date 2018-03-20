<?php
/**
 * This is the index page and controller for our Flashcards website.
 * It manages all routes.
 * index.php
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>, Jen Shin <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

// Require the autoload file
require_once('vendor/autoload.php');

include_once('models/db-functions.php');
include_once ('models/data-validation.php');

// Start a session
session_start();

// Create an instance of the Base class
$f3 = Base::instance();

// Connect to the database (temp)
$dbh = connect();

// Define a default route (Home Page/View Decks Collection)
$f3->route('GET|POST /', function($f3) {

    global $dbh;

    //if not logged in, cannot get to inner pages
    if(!isset($_SESSION['userId'])) {
        $f3->reroute('/login');
    }

    // get userId of currently logged in user
    $userId = $_SESSION['userId'];

    // Retrieve decks for logged in user from database
    $result = getUserDecks($userId);
    if ($result == null) {
        $f3->set('noDecks', "Please create a new Deck =)");
    }

    $decks = array();

    foreach($result as $deckOption) {
        $decks[$deckOption['deckId']] = $deckOption['deckName'];
    }

    // set decks array into hive
    $f3->set('options', $result);


    if(isset($_POST['submit'])) {
        // retrieve values from POST array

        $deck = $_POST['deckOption'];
        $choice = $_POST['choice'];

        // set deckId to a session to be grabbed by edit and play routes/pages
        $_SESSION['deckId'] = $deck;

        // Get deck name based on deckId
        $_SESSION['deckName'] = $decks[$deck];

        // Used for sticky radios
        $f3->set("choice", $choice);

        $isValid = true;

        // if data is valid, retrieve deck from database
        if($isValid) {
            // Reroute to next page depending on choice
            if ($choice == "play") {
                $f3->reroute('/play');
            } else if ($choice == "edit") {
                $f3->reroute('/edit');
            } else {
                $choiceErr = "Please select edit or play.";
                $f3->set('choiceErr', $choiceErr);
            }
        }
    }

    $template = new Template();
    echo $template->render('views/home.html');
});

// Define a route for playing/viewing flashcards
$f3->route('GET|POST /play', function($f3) {
    //if not logged in, cannot get to inner pages
    if(!isset($_SESSION['userId'])) {
        $f3->reroute('/login');
    }

    $deckId = $_SESSION['deckId'];
    $deckName = $_SESSION['deckName'];

    // retrieve all flashcards with given deckId from database
    $result = getDeckFlashcards($deckId);

    // convert retrieved deck into an associative array
    $flashcards = array();
    foreach($result as $flashcard) {
        $question = $flashcard['question'];
        $answer = $flashcard['answer'];
        $flashcards[$question] = $answer;
    }

    // shuffle deck while preserving key=>value pairs
    $shuffled = array();
    while(!empty($flashcards)) {
        // select one random key from our associative array
        $randomKey = array_rand($flashcards, 1);

        // add key to new array
        $shuffled[$randomKey] = $flashcards[$randomKey];

        // unset key in original array
        unset($flashcards[$randomKey]);
    }

    // set shuffled flashcards array to hive
    $f3->set('flashcards', $shuffled);
    $f3->set('deckName', $deckName);

    $template = new Template();
    echo $template->render('views/view-flashcards.html');
});

// Define a route for editing flashcards from an existing deck
$f3->route('GET|POST /edit', function($f3) {
    //if not logged in, cannot get to inner pages
    if(!isset($_SESSION['userId'])) {
        $f3->reroute('/login');
    }

    $deckId = $_SESSION['deckId'];
    // get userId of currently logged in user
    $userId = $_SESSION['userId'];

    // Retrieve decks for logged in user from database
    $result = getUserDecks($userId);

    $decks = array();
    foreach($result as $deckOption) {
        $decks[$deckOption['deckId']] = $deckOption['deckName'];
    }

    $result = getDeckFlashcards($deckId);

    // set flashcards array to hive
    $f3->set('flashcards', $result);
    // set deck information to hive
    $f3->set('deckId', $deckId);
    $f3->set('deckName', $decks[$deckId]);

    $template = new Template();
    echo $template->render('views/edit-flashcards.html');
});

// Define a route for creating a new deck
$f3->route('GET|POST /create', function($f3) {

    //if not logged in, cannot get to inner pages
    if(!isset($_SESSION['userId'])) {
        $f3->reroute('/login');
    }

    $userId = $_SESSION['userId'];

    global $dbh;
    $deckName = "";
    $question = array();
    $answer = array();
    $isValid = true;

    if(isset($_POST["submit"])) {
        if(empty($_POST['deckName'])) {
            $invalidDeckName = "Deck name cannot be empty.";
            //set error message into hive
            $f3->set('invalidDeckName', $invalidDeckName);
            $isValid = false;
        } else { //deckName cannot be empty
            $deckName = $_POST['deckName'];

        }
        $question = $_POST['question'];
        $answer = $_POST['answer'];

        if($isValid) {
            $success = addNewDeck($deckName, $userId);
            $deckId = $dbh->lastInsertId();

            if ($success) {
                // check if there are cards to add
                if (sizeof($question) > 0) {
                    //create new object
                    $flashcardDeck = new QuestionAnswer($deckName, $deckId, $question, $answer);

                    addPairsIntoDatabase($flashcardDeck);
                }
                $f3->reroute('/');

            }
        }
    }

    $template = new Template();
    echo $template->render('views/create-new-deck.html');
});

// Define route for login
$f3->route('GET|POST /login', function($f3) {

    global $dbh;
    $email = "";
    $password = "";

    $mismatchedPassword = "";
    $invalidEmail = "";

    if(isset($_POST['submit'])) {

        $isValidEmail = true;

        if(!empty($_POST['username'])) {
            $email = $_POST['username']; // email
        } else {
            $invalidEmail = "Please enter a username.";
            $isValidEmail = false;
        }

        if(!empty($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $mismatchedPassword = "Please enter a password.";
        }

        /* Set Hive Variables */
        $f3->set('invalidEmail', $invalidEmail);
        $f3->set('mismatchedPassword', $mismatchedPassword);

        if($isValidEmail) {
            // check if username and password match stored credentials

            // retrieve userid
            $result = getUser($email);
            if ($result == null) {
                $invalidEmail = $email." does not exist.";
                $f3->set('invalidEmail', $invalidEmail);
            } else {
                // check password
                if(!empty($password)) {
                    // Check if stored password matches input from password field
                    if ($result['password'] != sha1($password)) {

                        $mismatchedPassword = "Password does not match the password stored for ".$email;
                    } else {
                        //add user id to session
                        $_SESSION['userId'] = $result['userId'];
                        setcookie("user", "returning");
                        $f3->reroute("/");
                    }
                } else {
                    $mismatchedPassword = "Please enter a password.";
                }

                /* Set Hive Variable */
                $f3->set('mismatchedPassword', $mismatchedPassword);

            } // end of else

        } // end of $isValidEmail

    }

    $template = new Template();
    echo $template->render('views/login.html');
});

// Define route for registration of new users
$f3->route('GET|POST /register', function($f3) {

    global $dbh;

    $invalidEmail = "";

    if(isset($_POST['submit'])) {

        $email = $_POST['username']; // email
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $isValid = true;
        $found = false;

        if (empty($email)) {
            $invalidEmail = "Please enter an email.";
            $isValid = false;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $invalidEmail = "$email is not a valid email address";
            $isValid = false;
        } else {
            // retrieve all users currently stored in database
            $allUsers = getAllUsers();
            // if result of query is not empty,
            // check if entered username/email matches any found in database
            if(!empty($allUsers)) {
                foreach($allUsers as $user) {
                    if(in_array($email, $user)) {
                        $found = true;
                        break; // if found, break out of loop
                    }
                }
                if($found) {
                    $invalidEmail = $email." already in use.";
                    $isValid = false;
                }
            }
        }

    //validate passwords
        $mismatchedPassword = notEmptyMismatched($password, $password2);
        if($mismatchedPassword == null) {
            if(!validPassword($password)) {
                $mismatchedPassword = "Passwords must be at least 6 characters long and contain an uppercase letter,
                 a lowercase letter, a number, and a symbol.";
                $isValid = false;
            }

        } else {
            $isValid = false;
        }

        /* Set Hive Variables */
        $f3->set('invalidEmail', $invalidEmail);
        $f3->set('mismatchedPassword', $mismatchedPassword);


        /* if no errors */
        if($isValid)
        {
            // call function to addUser
            $success = addNewUser($email, $password);

            // if user successfully added to database, get user id and reroute to home page
            if($success)
            {
                //add user id to session
                $_SESSION['userId'] = $dbh->lastInsertId();

                // reroute new user to create a deck page
                setcookie("user", "new");
                $f3->reroute("/create");
            }
        }
    }

    $template = new Template();
    echo $template->render('views/register.html');
});

//logout page
$f3->route('GET|POST /logout', function($f3) {
    session_destroy();
    $f3 -> reroute('/login');
});

// Run fat free
$f3->run();