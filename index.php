<?php
/**
 * This is the index page and controller for our Flashcards website.
 * It manages all routes.
 * index.php
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>, Jen Shin <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

// Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require the autoload file
require_once('vendor/autoload.php');

//temp
include_once('models/db-functions.php');

// Start a session
session_start();

// Create an instance of the Base class
$f3 = Base::instance();

// Connect to the database (temp)
$dbh = connect();

// Set debug level 0 = off, 3 = max level
$f3->set('DEBUG', 3);

// Define a default route (Home Page/View Decks Collection)
$f3->route('GET|POST /', function($f3) {

//    global $dbh;

    //if not logged in, cannot get to inner pages
    if(!isset($_SESSION['userId'])) {
        $f3->reroute('/login');

    }

    // get userId of currently logged in user
    $userId = $_SESSION['userId'];

    // Retrieve decks for logged in user from database
    $result = getUserDecks($userId);

    /*
     * Array received from database
     *
     * Array (
     * [0] => Array (
     *              [deckId] => 24
     *              [deckName] => myFirstDeck
     *               )
     * [1] => Array (
     *              [deckId] => 25
     *              [deckName] => Second Deck is the Best
     *              )
     *      )
     */

    // set decks array into hive
    $f3->set('options', $result);


    if(isset($_POST['submit']))
    {
        // retrieve values from POST array

        // this retrieves the value attribute stored in each option. This value is the deckId.
        $deck = $_POST['deckOption'];

        $choice = $_POST['choice'];
//        $choices = array('edit', 'play');

        // set deckId to a session to be grabbed by edit and play routes/pages
        $_SESSION['deckId'] = $deck;

        // deckId test
        /*echo $deck;*/

//        $f3->set("deck", $deck);

        // Used for sticky radios
        $f3->set("choice", $choice);

        $isValid = true;

        // validate data
        include 'models/data-validation.php';

        /*$deckName = array();

        foreach($result as $row) {
            array_push($deckName, $row['deckName']);
//        $deckName[] = $row['deckName'];
        }*/

        // choose a deck
        /*if (!validSelection($deck, $result)) {
            $isValid = false;
        }

        if(!validSelection($choice, $choices)) {
            $isValid = false;
        }*/

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
        // store deck in session?

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

    // retrieve all flashcards with given deckId from database
    $result = getDeckFlashcards($deckId);

    // convert retrieved deck into an associative array
    $flashcards = array();
    foreach($result as $flashcard)
    {
        $question = $flashcard['question'];
        $answer = $flashcard['answer'];
        $flashcards[$question] = $answer;
    }

    // shuffle deck while preserving key=>value pairs
    $shuffled = array();
    while(!empty($flashcards))
    {
        // select one random key from our associative array
        $randomKey = array_rand($flashcards, 1);

        // add key to new array
        $shuffled[$randomKey] = $flashcards[$randomKey];

        // unset key in original array
        unset($flashcards[$randomKey]);
    }

    // set shuffled flashcards array to hive
    $f3->set('flashcards', $shuffled);

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
//    echo "<p>Selected Deck has id of: $deckId</p>"; // temp

    $result = getDeckFlashcards($deckId);
    print_r($result);

    /* Array retrieved from database
     *
     * Array (
     * [0] => Array ( [pairId] => 9
     *                [question] => Is this a question?
     *                [answer] => I suppose it is. )
     *
     * [1] => Array ( [pairId] => 10
     *                [question] => "Peter Piper picked a peck of pickled peppers. A p
     *                [answer] => Alliteration )
     *
     * [2] => Array ( [pairId] => 11
     *                [question] => A line of verse with five metrical feet, each cons
     *                [answer] => iambic pentameter )
     *
     * [3] => Array ( [pairId] => 12
     *                [question] => "As brave as a lion" is an example of what poetic
     *                [answer] => Simile )
     *
     * [4] => Array ( [pairId] => 13
     *                [question] => Onomatopoeia is defined as _________________.
     *                [answer] => The naming of a thing or action by a vocal imitati ) )
     */

    // set flashcards array to hive
    $f3->set('flashcards', $result);

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

        //print_r($question);

        //test
        //print_r($_POST);
        if($isValid) {
            $success = addNewDeck($deckName, $userId);
            $deckId = $dbh->lastInsertId();
            echo "<p>Deck Id: " . $deckId . "</p>";

            if ($success) {
                echo "This was successful";

                // check if there are cards to add
                if (sizeof($question) > 0) {
                    //create new object
                    //$newDeck = new questionAnswer($deckName, $question, $answer);
                    echo "success is okay.";
                    addPairsIntoDatabase($question, $answer, $deckId);
                }

            } else {
                $invalidDeckName = $deckName . " is already taken. Create a deck with different name.";
                $f3->set('invalidDeckName', $invalidDeckName);

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
        // get post variables (username,password,password2)
        // Array ( [username] => Kianna [password] => 123 [password2] => 123 [submit] => Create Account )

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

        if($isValidEmail)
        {
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
                        /*$f3->set('mismatchedPassword', $mismatchedPassword);*/
                    } else {
                        //add user id to session
                        $_SESSION['userId'] = $result['userId'];
                        $f3->reroute("/");
                    }
                } else {
                    $mismatchedPassword = "Please enter a password.";
                }

                /* Set Hive Variable */
                $f3->set('mismatchedPassword', $mismatchedPassword);

            } // end of else

        } // end of $isValidEmail

    } // isset

    $template = new Template();
    echo $template->render('views/login.html');
});

// Define route for registration of new users
$f3->route('GET|POST /register', function($f3) {

    global $dbh;

    $mismatchedPassword = "";
    /*$emailInUse= "";*/
    $invalidEmail = "";

    if(isset($_POST['submit'])) {
        // get post variables (username,password,password2)
        // Array ( [username] => Kianna [password] => 123 [password2] => 123 [submit] => Create Account )

        $email = $_POST['username']; // email
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $isValid = true;
        $found = false;

        // validation (move to different page later)
        // validate email

        /* Array retrieved from database of stored users */

        //        Array (
        // [0] => Array ( [email] => )
        // [1] => Array ( [email] => jen@mail.com )
        // [2] => Array ( [email] => jen@test.om )
        // [3] => Array ( [email] => jk@lol.com )
        // [4] => Array ( [email] => jshin13@mail.com )
        // [5] => Array ( [email] => ki@mail.com )
        // [6] => Array ( [email] => kiwi@fruitylicious.com )
        // [7] => Array ( [email] => test3@email.com ) )

        if (empty($email)) {
            $invalidEmail = "Please enter an email.";
            /*$f3->set('emailFormat', $emailNotValidFormat);*/
            $isValid = false;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $invalidEmail = "$email is not a valid email address";
            /*$f3->set('emailFormat', $emailNotValidFormat);*/
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

        // validate password
        if(empty($password) || empty($password2)) {
            $mismatchedPassword = "Please enter a password";
            $isValid = false;
        }
        if($password != $password2) {
            $mismatchedPassword = "Passwords do not match.";
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
                $f3->reroute("/");
            } /*else {
                $emailInUse = $email." already in use.";
                $f3->set('emailInUse', $emailInUse);
            }*/
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