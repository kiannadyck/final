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
        echo "Connected to database!";
        return $dbh;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return;
    }
}

function addNewUser()
{
    // first time registration
}

function getUser()
{
    // after login
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