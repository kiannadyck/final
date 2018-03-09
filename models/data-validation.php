<?php
/* Kianna Dyck
 * Jen Shin
 * 03/08/2018
 * This file contains data validation functions.
 */

/* Home Page */

// choose a deck select input
// edit/play radio buttons
/**
 * @param $input Selection from user
 * @param $options Options user can choose from to select
 * @return bool returns true if selected option is one of the available options to choose from, else returns false.
 */
function validSelection($input, $options)
{
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


}

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
 * @param $question Input from user for question.
 * @param $answer Input from user for answer.
 * @return bool Returns true if both question and answer is entered. False otherwise.
 */
function validPair($question, $answer)
{
    return !empty($question) && !empty($answer);
}

/* Login Page */

// valid username/email entered

// password matches with password stored for given username/email

