<?php
/**
 * This file is the data validation page for our flashcards website.
 * It validates if passwords match or if fields are empty.
 *
 * data-validation.php
 * 03/08/2018
 *
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>, Jen Shin<jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

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

/**
 * Checks if passwords match
 * @param $password string, password
 * @param $password2 string, confirm password
 * @return null|string error message
 */
// validate password
function notEmptyMismatched($password, $password2)
{
    if (empty($password) || empty($password2)) {
        return "Please enter a password";
    }
    if ($password != $password2) {
        return "Passwords do not match.";
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


