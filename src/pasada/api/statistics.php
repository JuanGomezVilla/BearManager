<?php

//PLUGIN IMPORT
require("../php/QuickAPI.php"); //Script to create APIs
require("../php/ContextDB.php"); //Script to handle with databases
require("../php/UtilsWeb.php"); //Web utils tools
require("../config.php"); //Main configurations
require("../languages.php"); //Languages

//Check if the use of the API is enabled
if($config["api_enabled"]){
    //Creation of the API, of type GET
    $api = new QuickAPI("GET");
    $status = $data = null;

    //Create a connection to the database with the configuration
    $connection = new ContextDB(
        $config["database"]["host"], //Hostname
        $config["database"]["user"], //Username
        $config["database"]["password"] //Password
    );

    //Check if there is a connection to the database
    if($connection -> is_database_connection()){
        //Change the status
        $status = "OK";

        //Capture the statistics data
        $data = $connection -> execute_query("CALL getStatistics()")[0];

        //Process the captured data
        $data["publications"] = (int) $data["publications"]; //Convert value to integer
        $data["students"] = (int) $data["students"]; //Convert value to integer
        $data["languages"] = count(array_keys($languages)); //Gets the number of keys, i.e. languages
    } else {
        //Set the status to error
        $status = "error";
    }

    //Final return with status and data
    $api -> return_json(array("status" => $status, "data" => $data), true);
} else {
    //If the API is disabled, Error 404, Not Found
    http_response_code(404);
}

?>