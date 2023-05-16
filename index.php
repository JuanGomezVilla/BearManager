<?php

//Starts a session
session_start();

//PLUGIN IMPORT
require("php/ContextDB.php"); //Script to handle with databases
require("php/UtilsWeb.php"); //Web utils tools
require("config.php"); //Main configurations

//Check if a session exists, in that case it sends directly to the control panel
if(UtilsWeb::in_SESSION("nickname")) UtilsWeb::redirect("/panel"); 

//Create a connection to the database with the configuration
$connection = new ContextDB(
    $config["database"]["host"], //Hostname
    $config["database"]["user"], //Username
    $config["database"]["password"] //Password
);

//Verify that in the POST there is a value for the username and password
if(UtilsWeb::in_POST("username") && UtilsWeb::in_POST("password")){
    //Capture both values ​​and encrypt the password to MD5
    $username = UtilsWeb::get_from_POST("username");
    $password = md5(UtilsWeb::get_from_POST("password"));

    //Capture the user with that data
    $user = $connection -> execute_query(
        "SELECT username, nickname, userType, language FROM users WHERE username = :username AND password = :password LIMIT 1",
        array(":username" => $username, ":password" => $password) //Parameters
    );

    //If data exists, it is because the user exists
    if($user){
        //Sets the value of the username, the nickname and the user type
        $_SESSION["username"] = $user[0]["username"];
        $_SESSION["nickname"] = $user[0]["nickname"];
        $_SESSION["userType"] = $user[0]["userType"];
        $_SESSION["language"] = $user[0]["language"];

        //Redirect to control panel
        UtilsWeb::redirect("/panel");
    } else {
        //Username error message
        $_SESSION["error"] = $username;
    }

    //Final redirect to avoid resubmission of the form with POST
    UtilsWeb::redirect("/");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Title and icon -->
        <title>Login - <?php echo $config["title"] ?></title>
        <link rel="icon" href="/assets/icon.ico">
        <meta charset="UTF-8" />

        <!-- Settings -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/login.css">
    </head>
    <body>
        <!-- Centered container -->
        <div class="container-center">
            <?php
                //Check if there is a connection to the database
                if($connection -> is_database_connection()){

            ?><form method="POST">
                <p style="margin-top:0;font-size:25px">Login</p>
                <input class="form-row" type="text" name="username" placeholder="&#128100; User..." <?php 
                    if(UtilsWeb::in_SESSION("error")) echo "value=\"" .$_SESSION["error"] ."\"";
                ?> required>
                <input class="form-row" type="password" name="password" placeholder="&#128274; Password..." required>
                <button>Login</button>
            <?php //If there is an error in the session, show an error message
                if(UtilsWeb::in_SESSION("error")) {
                ?>
                <p class="error-color" style="margin-bottom: 0;margin-top:23px;">Incorrect data</p>
        <?php } ?></form>
        <?php } else { ?><p style="color:#FFFFFF;font-size:30px">Service not available</p><?php echo "\n". str_repeat(" ", 8); } ?></div>
    </body>
</html><?php

//Empty the possible error value of the session
session_unset();

//Finish the connection with the database
$connection -> finish();

?>