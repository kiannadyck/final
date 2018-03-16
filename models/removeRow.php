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

$pairId = $_POST['pairId']; //whatever data is passed in

include_once('db-functions.php');
$dbh = connect();

echo deleteRow($pairId);