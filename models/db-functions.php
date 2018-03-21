<?php
/**
 * This file contains functions for flashcard website.
 *
 * db-functions.php
 * @author Jen Shin <jshin13@mail.greenriver.edu>, Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018
 */

// require database connection file
$j = "jshingre";
$k = "kdyckgre";

require ("/home/".$j."/final_config.php");

/**
 * Creates connection to database.
 * @return PDO, database connection
 */

function connect()
{
    try {
        // instantiate a PDO object using a PDO constructor
        $dbh = new PDO(DB_DSN,DB_USERNAME,DB_PASSWORD);
        return $dbh;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return;
    }
}

/**
 * Adds new user to database table.
 * @param $email user email
 * @param $password user password
 * @return bool true if successfully added, else false
 */

function addNewUser($email, $password)
{
    // first time registration
    global $dbh;

    // 1. define the query
    $sql = "INSERT INTO loginCredentials (email, password) VALUES (:email, :password)";

    // 2. prepare the statement
    $statement = $dbh->prepare($sql);

    // 3. bind parameters
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':password', sha1($password), PDO::PARAM_STR);

    // 4. execute the statement
    $success = $statement->execute();

    return $success;

}

/**
 * Gets user Id.
 * @param $email user email
 * @return int, user Id
 */

function getUser($email)
{
    // after login
    global $dbh;

    // 1. define the query
    $sql = "SELECT * FROM loginCredentials WHERE email = :username";

    // 2. prepare the statement
    $statement = $dbh->prepare($sql);

    // 3. bind parameters
    $statement->bindParam(':username', $email, PDO::PARAM_STR);

    // 4. execute the statement
    $statement->execute();

    // 5. Return the result
    $result = $statement->fetch();

    // returns userId
    return $result;
}

/**
 * Adds a new deck.
 * @param $deckName string, name of deck
 * @param $userId int, user id
 * @return bool true if added to database, else false
 */

function addNewDeck($deckName, $userId)
{
    //database connection
    global $dbh;

    // 1. define the query
    $sql = "INSERT INTO decks (deckName, userId) VALUES (:deckName, :user)";

    // 2. prepare the statement
    $statement = $dbh->prepare($sql);

    // 3. bind parameters
    $statement->bindParam(':deckName', $deckName, PDO::PARAM_STR);
    $statement->bindParam(':user', $userId, PDO::PARAM_INT);

    // 4. execute the statement
    $success = $statement->execute();

    return $success;
}

/**
 * Adds arrays of flashcard pairs into database.
 * @param $deckObject QuestionAnswer object
 * @return bool true if added successfully into database, else false
 */

function addPairsIntoDatabase($deckObject)
{
    global $dbh;

    $isValid = true;

    //get question and answer arrays from user object
    $answerArray = $deckObject->getAnswers();
    $questionArray = $deckObject->getQuestions();

    // get deckId from userObject
    $deckId = $deckObject->getDeckId();

    //loop through array and insert each flashcard into database
    for($i = 0; $i < sizeof($questionArray); $i++) {
        $question = $questionArray[$i];
        $answer = $answerArray[$i];

        $sql = "INSERT INTO flashcard (question, answer, deckId) VALUES (:question, :answer, :deckId)";

        // 2. prepare the statement
        $statement = $dbh->prepare($sql);

        // 3. bind parameters
        $statement->bindParam(':question', $question, PDO::PARAM_STR);
        $statement->bindParam(':answer', $answer, PDO::PARAM_STR);
        $statement->bindParam(':deckId', $deckId, PDO::PARAM_INT);

        $success = $statement->execute();

        if(!$success) {
            $isValid = false;
        }
    }
    return $isValid;
}

/**
 * Adds a new question and answer pair to database.
 * @param $question string, user input
 * @param $answer string, user input
 * @param $deckId int, deck id
 * @return bool true if added to database, else false
 */

function addRow($question, $answer, $deckId)
{
    global $dbh;

    $sql = "INSERT INTO flashcard (question, answer, deckId) VALUES (:question, :answer, :deckId)";

    // 2. prepare the statement
    $statement = $dbh->prepare($sql);

    // 3. bind parameters
    $statement->bindParam(':question', $question, PDO::PARAM_STR);
    $statement->bindParam(':answer', $answer, PDO::PARAM_STR);
    $statement->bindParam(':deckId', $deckId, PDO::PARAM_INT);

    $success = $statement->execute();
    return $success;
}

/**
 * Retrieves user decks from database.
 * @param $userId int, user id
 * @return array user deck rows from database
 */

function getUserDecks($userId)
{
    // query database to get a user's collection of decks

    global $dbh;

    // 1. define the query
    $sql = "SELECT deckId, deckName FROM decks WHERE userId = :userId";

    // 2. prepare the statement
    $statement = $dbh->prepare($sql);

    // 3. bind parameters
    $statement->bindParam(':userId', $userId, PDO::PARAM_INT);

    // 4. execute the statement
    $statement->execute();

    // 5. Return the result
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // returns all decks with userId
    return $result;
}

/**
 * Retrieves deck flashcards (question and answers) from database.
 * @param $deckId int, deck id
 * @return array answers and questions from database
 */

function getDeckFlashcards($deckId)
{
    // after user selects a deck, get flashcards with that deck's id
    global $dbh;

    // 1. define the query
    $sql = "SELECT pairId, question, answer FROM flashcard WHERE deckId = :deckId";

    // 2. prepare the statement
    $statement = $dbh->prepare($sql);

    // 3. bind parameters
    $statement->bindParam(':deckId', $deckId, PDO::PARAM_INT);

    // 4. execute the statement
    $statement->execute();

    // 5. Return the result
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    // returns all flashcards with given deckId
    return $result;
}

/**
 * Retrieves all users from database.
 * @return array users from database
 */
function getAllUsers()
{
    global $dbh;

    // define the query
    $sql = "SELECT email FROM loginCredentials";

    // prepare the statement
    $statement = $dbh->prepare($sql);

    // execute the statement
    $statement->execute();

    // Return the result
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

/**
 * Deletes a question answer pair from database.
 * @param $pairId int, pair id
 * @return bool
 */
function deleteRow($pairId)

{
    global $dbh;

    $select = "DELETE from flashcard WHERE pairId=:pairId";

    // prepare statement
    $statement = $dbh->prepare($select);

    // bind parameters
    $statement->bindValue(':pairId', $pairId);

    // execute statement
    $success = $statement->execute();

    return $success;
}

/**
 * Updates question and answer pair.
 * @param $pairId int, pair id
 * @param $question string, question
 * @param $answer string, answer
 * @return bool true if updated, else false
 */
function updateRow($pairId, $question, $answer)
{
    global $dbh;

    $select = "UPDATE flashcard SET question = :question, answer = :answer WHERE pairId=:pairId";

    // prepare statement
    $statement = $dbh->prepare($select);

    // bind parameters
    $statement->bindValue(':pairId', $pairId);
    $statement->bindValue(':answer', $answer);
    $statement->bindValue(':question', $question);

    // execute statement
    $success = $statement->execute();

    return $success;
}

/**
 * Updates deck name on edit page.
 * @param $deckId int, deck id
 * @param $deckName string, deck name
 * @return bool true if updated successfully, else false
 */
function updateDeckName($deckId, $deckName)

{
    global $dbh;

    $select = "UPDATE decks SET deckName = :deckName WHERE deckId = :deckId";

    // prepare statement
    $statement = $dbh->prepare($select);

    // bind parameters
    $statement->bindValue(':deckId', $deckId);
    $statement->bindValue(':deckName', $deckName);

    // execute statement
    $success = $statement->execute();
    return $success;
}

/**
 * Deletes a whole deck and all its frlashcards from the database.
 * @param $deckId int, deck id
 * @return bool true if updated successfully, else false
 */

function deleteDeck($deckId)
{
    global $dbh;

    //delete flashcards from deck

    $select = "DELETE from flashcard WHERE deckId=:deckId";

    // prepare statement
    $statement = $dbh->prepare($select);

    // bind parameter
    $statement->bindValue(':deckId', $deckId);

    // execute statement
    $success = $statement->execute();

    // if flashcards successfully deleted from deck, delete deck
    if ($success) {
        $select = "DELETE from decks WHERE deckId=:deckId";

        // prepare statement
        $statement = $dbh->prepare($select);

        // bind parameters
        $statement->bindValue(':deckId', $deckId);

        // execute statement
        $success = $statement->execute();
    }

    return $success;
}