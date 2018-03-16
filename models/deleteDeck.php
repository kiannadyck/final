<?php
/**
 * This deletes a whole deck from our database.
 *
 * Date: 3/16/18
 * Time: 11:49 AM
 * deleteDeck.php
 *
 * @author Jen Shin <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

require_once('models/db-functions.php');
$deckId = $_SESSION['deckId']; //whatever data is passed in

//connect to db
$cnxn = connect();

//update using pairId
/*UPDATE table_name
SET column1 = value1, column2 = value2, ...
WHERE condition;*/

$select = "DELETE from deck WHERE deckId=:deckId";
$statement = $cnxn->prepare($select);
$statement->bindValue(':deckId', $deckId);

$success = $statement->execute();

