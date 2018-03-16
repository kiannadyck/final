<?php
/**
 * This file updates a row dor the edit page of flashcards website.
 * Date: 3/16/18
 * Time: 11:33 AM
 * update.php
 * @author Jen Shin <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

require_once('models/db-functions.php');
$pairId = ""; //whatever data is passed in
$answer = $_POST['answer'];
$question = $_POST['question'];

//connect to db
$dbh= connect();

//update using pairId
/*UPDATE table_name
SET column1 = value1, column2 = value2, ...
WHERE condition;*/

$select = "UPDATE deck SET question = 'question=:question', answer =  'answer=:answer' WHERE pairId=:pairId";
$statement = $cnxn->prepare($select);
$statement->bindValue(':pairId', $pairId);
$statement->bindValue(':answer', $answer);
$statement->bindValue(':question', $question);

$success = $statement->execute();

