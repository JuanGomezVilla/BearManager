<?php

//Starts a session
session_start();

//PLUGIN IMPORT
require("../php/QuickAPI.php"); //Script to create APIs
require("../php/ContextDB.php"); //Script to handle with databases
require("../php/UtilsWeb.php"); //Web utils tools
require("../config.php"); //Main configurations
require("../languages.php"); //Languages

//Check if the use of the API is enabled
if(UtilsWeb::in_SESSION("username")){
    //Creation of the API, of type GET
    $api = new QuickAPI("PUT");
    $status = $data = $result = null;
    $jsonBody = $api -> get_json_body();

    //Create a connection to the database with the configuration
    $connection = new ContextDB(
        $config["database"]["host"], //Hostname
        $config["database"]["user"], //Username
        $config["database"]["password"] //Password
    );

    //Check if there is a connection to the database
    if($connection -> is_database_connection()){
        //Obtención del usuario
        $userType = $connection -> execute_query(
            "SELECT userType FROM users WHERE username = :username LIMIT 1",
            array(":username" => $_SESSION["username"]),
            PDO::FETCH_COLUMN
        )[0];
        
        if($userType == "administrator" || $userType == "teacher"){
            //Change the status
            $status = "OK";

            $result = (bool) $connection -> execute_query(
                "SELECT changeStatusPost(:id) AS status;",
                array(":id" => $jsonBody["idpost"]),
                PDO::FETCH_COLUMN
            )[0];
        } else {
            $status = "error";
        }
    } else {
        //Set the status to error
        $status = "error";
    }

    //Final return with status and data
    $api -> return_json(array("status" => $status, "result" => $result), true);
} else {
    //If the API is disabled, Error 404, Not Found
    http_response_code(404);
}

?>