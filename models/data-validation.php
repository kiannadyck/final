<?php
/**
 * This file is the data validation page for our flashcards website.
 *
 * data-validation.php
 * 03/08/2018
 *
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>, Jen Shin<jshin13@mail.greenriver.edu>
 * @copyright 2018
 */


// choose a deck select input
// edit/play radio buttons
/**
 *
 * @param $input Selection from user
 * @param $options Options user can choose from to select
 * @return bool returns true if selected option is one of the available options to choose from, else returns false.
 */
/*function validSelection($input, $options)
{*/
/*
Array ( [0] => Array ( [deckName] => Kianna )
[1] => Array ( [deckName] => Bob )
[2] => Array ( [deckName] => JenKianna )
*/

    // input might be Kianna
    // options is above


    /*echo in_array($input, $options);*/

/*    foreach($options as $key=>$value) {
        if()
    }*/

/*    if (!empty($input) && !empty($options)) {
        if (!inarray($input, $options)) {
            return false;
        }

        return true;
    }

    return false;*/


//}

/* Create New Deck Page */

// Deck Name (Cannot be empty)
/**
 * @param $deck The name of the deck inputted by the user.
 * @return bool Returns true if deckName is entered, false otherwise.
 */
/*function validDeckName($deck, $allDecks)
{
    // input field cannot be empty
    // user cannot have a deck with name already in their collection
    return !empty($deck) ;
}*/

// Question & Answer - if question is entered, answer is also entered and vice versa

/**
 * Checks if input for pairs are empty.
 * @param $question string, Input from user for question.
 * @param $answer string, Input from user for answer.
 * @return bool Returns true if both question and answer is entered. False otherwise.
 */
function validPair($question, $answer)
{
    return !empty($question) && !empty($answer);
}

/* Login Page */

// valid username/email entered
// validate password
function notEmptyMismatched($password, $password2)
{
    if (empty($password) || empty($password2)) {
        return "Please enter a password";
        //$isValid = false;
    }
    if ($password != $password2) {
        return "Passwords do not match.";
        //$isValid = false;
    }
    return null;
}

/**
 * Checks if a password has one uppercase, one lowercase, one number, one special char, and is atleast 6 char long.
 * @param $password string, user input
 * @return bool true if valid password, else false
 */
function validPassword($password) {
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$specialChars || !$uppercase || !$lowercase || !$number || strlen($password) < 5) {
        return false;
    }
    return true;
}

// password matches with password stored for given username/email

