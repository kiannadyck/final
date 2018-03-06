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