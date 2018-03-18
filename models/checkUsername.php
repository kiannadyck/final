<?php
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
