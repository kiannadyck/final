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

function getUserDecks()
{
    // query database to get a user's collection of decks
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