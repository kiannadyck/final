<?php
/*
 * This file checks username availability.
 * checkUsername.php
 *
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>, Jen Shin <jshin13@mail.greenriver.edu>
 * @copyright 2018
 */

//include files that connect to database and calls functions
include_once('db-functions.php');

//instantiate connection
$dbh = connect();

$email = $_POST['email'];

$availableUsername = checkUsername($email);

// if not available, echo an error message
if (!$availableUsername) {
    echo $email." already in use.";
} else {
    echo "success";
}

/**
 * Compares entered username email with username emails stored in database.
 * @param $email string
 * @return bool
 */
function checkUsername($email)
{
    $allUsers = getAllUsers(); // retrieve all usernames stored in database

    if(!empty($allUsers)){
        foreach($allUsers as $user) {
            if(in_array($email, $user)) {
                return false; // if username found in database
            }
        }
    }

    // if username not found
    return true;

}
