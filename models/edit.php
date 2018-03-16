<?php
/**
 * This deletes a row from a table for the edit page.
 * Date: 3/16/18
 * Time: 11:45 AM
 * removeRow.php
 *
 * @author Jen Shin <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

// delete/add/update action
$action = $_POST['action'];

// flashcard table
$pairId = $_POST['pairId'];
$answer = $_POST['answer'];
$question = $_POST['question'];
$deckId = $_POST['deckId'];

include_once('db-functions.php');
include_once('data-validation.php');

$dbh = connect();

if($action == "deleteRow") {
    echo deleteRow($pairId);
} else if ($action == "updateRow") {
    if(validPair($question, $answer)) {
        echo updateRow($pairId, $question, $answer);
    } else {
        echo -1;
    }
} else if ($action == "addRow") {
    //if valid, call db function
    if(validPair($question, $answer)) {
        $result = addRow($question, $answer, $deckId);
        //return pairId
        echo $dbh->lastInsertId();

    } else { //not valid
        echo -1;
    }
} else if ($action == "updateDeckName") {
    echo updateDeckName($deckId, $deckName);
} else if ($action == "deleteDeck") {
    echo deleteDeck($deckId);
}
