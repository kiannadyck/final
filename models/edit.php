<?php
/**
 * This contains controller to edit, update, add, or delete a row from a table
 * or to delete the whole deck for the edit page.
 * Date: 3/16/18
 * Time: 11:45 AM
 * edit.php
 *
 * @author Jen Shin <jshin13@mail.greenriver.edu>, Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018
 */

// delete/add/update action
$action = $_POST['action'];

// flashcard table variables
$pairId = $_POST['pairId'];
$answer = $_POST['answer'];
$question = $_POST['question'];
$deckId = $_POST['deckId'];
$deckName = $_POST['deckName'];

//include files that connect to database and calls functions
include_once('db-functions.php');
include_once('data-validation.php');

//instantiate connection
$dbh = connect();
//delete a row
if($action == "deleteRow") {
    echo deleteRow($pairId);
//update a row
} else if ($action == "updateRow") {
    if(validPair($question, $answer)) {
        echo updateRow($pairId, $question, $answer);
    } else {
        echo -1;
    }
//add a new row
} else if ($action == "addRow") {
    //if valid, call db function
    if(validPair($question, $answer)) {
        $result = addRow($question, $answer, $deckId);
        //return pairId
        echo $dbh->lastInsertId();

    } else { //not valid
        echo -1;
    }
//update deck name
} else if ($action == "updateDeckName") {
    if(!empty($deckName)){

        echo updateDeckName($deckId, $deckName);
    } else {
        echo -1;
    }
//delete deck name
} else if ($action == "deleteDeck") {
    echo deleteDeck($deckId);
}
