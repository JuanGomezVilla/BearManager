<?php

//PLUGIN IMPORT
require("../php/QuickAPI.php"); //Script to create APIs
require("../php/ContextDB.php"); //Script to handle with databases
require("../php/UtilsWeb.php"); //Web utils tools
require("../config.php"); //Main configurations


//Creation of the API, of type GET
$api = new QuickAPI("GET", true);
$status = $data = null;

//Create a connection to the database with the configuration
$connection = new ContextDB(
    $config["database"]["host"], //Hostname
    $config["database"]["user"], //Username
    $config["database"]["password"] //Password
);

if($connection -> is_database_connection()){
    $result = $connection -> execute_query("CALL getAcceptedPublications()");
} else {
    $status = "error";
}

//Final return with status and data
$api -> return_json($result, true);

?>