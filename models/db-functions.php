<?php
// require database connection file
//(probably need to change path)
require ("/home/kdyckgre/final_config.php");

function connect()
{
    try {
        // instantiate a PDO object using a PDO constructor
        $dbh = new PDO(DB_DSN,
            DB_USERNAME,
            DB_PASSWORD);
//        echo "Connected to database!";
        return $dbh;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return;
    }
}

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

function addPairsIntoDatabase($question, $answer, $deckId)
{
    //get both arrays from user object
    //$answerArray = $object -> getQuestions();
    //$questionArray = $object -> getAnswers();
    global $dbh;

    $isValid = true;

    $answerArray = $answer;
    $questionArray = $question;

    //echo "HEY";
    print_r($answerArray);
    print_r($questionArray);

    //loop through array to assign variables
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


function getUserDecks($userId)
{
    // query database to get a user's collection of decks

    global $dbh;

    // 1. define the query
    // SELECT deckId, deckName FROM decks WHERE userId = 4
    $sql = "SELECT deckName FROM decks WHERE userId = :userId";

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

function getDeckFlashcards()
{
    // after user selects a deck, get flashcards with that deck's id
}

function saveDeck()
{
    // save deck name & flashcards inside deck
}

// edit flashcards in deck (add/remove/update)
function editDeck()
{

}