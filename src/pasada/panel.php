<?php

//Starts a session
session_start();

//PLUGIN IMPORT
require("php/ContextDB.php"); //Script to handle with databases
require("php/UtilsWeb.php"); //Web utils tools
require("config.php"); //Main configurations
require("languages.php"); //Languages

//Check if a session doesn't exists, in that case it sends directly to index
if(!UtilsWeb::in_SESSION("username")) UtilsWeb::redirect("/");

//Create a connection to the database with the configuration
$connection = new ContextDB(
    $config["database"]["host"], //Hostname
    $config["database"]["user"], //Username
    $config["database"]["password"] //Password
);

//Default language if there is no connection
$language = "en";

//Check that the connection to the database is correct
if($connection -> is_database_connection()){
    //Gets the settings from the database
    $settings = $connection -> execute_query("SELECT * FROM settings", null, PDO::FETCH_KEY_PAIR);

    //Gets user data
    $dataUser = $connection -> execute_query(
        "SELECT username, nickname, language, userType, timesLogged FROM users WHERE username = :username LIMIT 1",
        array(":username" => $_SESSION["username"])
    )[0];

    //Separate the data into variables from the previous query
    $userType = $dataUser["userType"];  //Type of user
    $username = $dataUser["username"];  //Username
    $nickname = $dataUser["nickname"];  //Name of individual
    $language = $dataUser["language"];  //Language
    $timesLogged = $dataUser["timesLogged"]; //Times logged in

    //If you have never logged in, there is a value for the password, and a password to confirm in the POST
    if($timesLogged == 0 && UtilsWeb::in_POST("newpassword") && UtilsWeb::in_POST("confirmpassword")){
        //TODO: contains spaces
        //Captures passwords, checks that at least one of them is not an empty string or contains spaces, and encrypts them directly
        $newPassword = UtilsWeb::get_from_POST("newpassword") != "" ? md5(UtilsWeb::get_from_POST("newpassword")) : null;
        $confirmPassword =  md5(UtilsWeb::get_from_POST("confirmpassword"));

        //If the passwords to be changed are not different and there is data for the captured user
        if($newPassword == $confirmPassword && $dataUser){
            //Update the password in the user database
            $connection -> execute_query(
                "UPDATE users SET password = :password WHERE username = :username",
                array(":password" => $newPassword, ":username" => $username)
            );

            //Increases by 1 the number of times the user has logged in to avoid repeat password changes
            $connection -> execute_query("CALL incrementTimeLogged(:username)", array(":username" => $username));
        }

        //Regardless of the result, it redirects to the panel
        UtilsWeb::redirect("/panel");
    }
}

//Get the texts in the selected language
$texts = $languages[$language];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Title and icon -->
        <title>Panel - <?php echo $config["title"] ?></title>
        <link rel="icon" href="/assets/icon.ico">
        <meta charset="UTF-8" />

        <!-- Settings -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">


    </head>
    <body><?php
        //Check if there is a connection to the database
        if($connection -> is_database_connection()){
            //If the number of times you logged in is 0, it gives the possibility to change the password
            if($timesLogged == 0){ ?>
        
        <style>

            body {
                overflow: hidden;
            }

            .contenedor-centrado {
                display: flex;
                align-items: center;
                position: absolute;
                top: 0;
                z-index: 9999;
                width: 100%;
                height: 100%;
                background-color: rgb(0,0,0,0.4);
                justify-content: center;
            }

            .contenedor-password {
                background-color: white;
                padding: 25px;
                border-radius: 15px;
                width: 20%;
                box-shadow: 0 0.5rem 1rem rgba(33, 37, 4, .35);
            }

        </style>
        <div class="contenedor-centrado">
            <form class="contenedor-password" method="POST">
                <p style="margin-top:0;font-size:25px"><?php echo $texts["description_change_password"]; ?></p>
                <div class="form-row">
                    <input name="newpassword" type="password" placeholder="<?php echo $texts["new_password"]; ?>..." required>
                </div>
                <div class="form-row">
                    <input name="confirmpassword" type="password" placeholder="<?php echo $texts["confirm_password"]; ?>..." required>
                </div>
                <div class="form-row">
                    <button id="buttonSavePassword"><?php echo $texts["save"]; ?></button>
                </div>
            </form>
        </div>
        <?php } echo "\n" ?>
        <!-- Navigation bar -->
        <nav class="navbar">
            <div class="nav-item"><?php echo $texts["home"]; ?></div>
            <div class="nav-item" onclick="location.href='/settings'"><?php echo $texts["settings"]; ?></div>
            <div class="nav-item" onclick="location.href='/faq'">FAQ</div>
            <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
        </nav>

        <!-- Title and description -->
        <div class="header">
            <h1><?php echo $texts["welcome"] .", $nickname"; ?></h1>
            <p class="header-text"><?php
                if($userType == "administrator") echo $texts["description_administrator"];
                else if($userType == "teacher") echo $texts["description_teacher"];
                else echo $texts["description_student"];
            ?></p>
            <hr class="header-hr">
            <p><?php echo $texts["description_more_info"]; ?></p>
            <button class="simple-button" id="buttonMoreInfo"><?php echo $texts["more_information"]; ?></button>
        </div>

        <!-- Main container -->
        <div class="container">
            <?php if($userType == "administrator" || $userType == "teacher"){
                //Capturar todas las entradas realizadas
                //$entradasRealizadas = $connection -> execute_query("CALL getPublications()");
            ?>
            <script src="/js/publications.js"></script>
            <h2>1. Entradas de alumnos</h2>
                <table id="postTable">
                    <tr>
                        <th>ID</th>
                        <th><?php echo $texts["user"]; ?></th>
                        <th><?php echo $texts["title"]; ?></th>
                        <th><?php echo $texts["content"]; ?></th>
                        <th><?php echo $texts["image"]; ?></th>
                        <th><?php echo $texts["accepted"]; ?></th>
                        <th><?php echo $texts["actions"]; ?></th>
                    </tr>
                <?php

                    foreach($entradasRealizadas as $entrada){
                        echo "<tr>";
                        echo "<td>". $entrada["id"] ."</td>";
                        echo "<td>". $entrada["student"] ."</td>";
                        echo "<td>". $entrada["title"] ."</td>";
                        echo "<td>". $entrada["content"] ."...</td>";
                        echo "<td><a target=\"_blank\" href=\"". $entrada["image"] ."\">Ver imagen</a></td>";
                        echo "<td><button class=\"simple-button\" style='border:none' id-post-change-status='". $entrada["id"] ."'>". ($entrada["accepted"] ? "&#128077;" : "&#128078;") ."</button></td>";
                        echo "<td style='display:flex'>";
                        echo "<button class=\"simple-button\" style='margin-right:10px'>Ver contenido</button>";
                        echo "<button class=\"simple-button error-color\" style='border:none' id-post-remove='". $entrada["id"] ."'>&#10060;</button>";
                        echo "</td>";
                        echo "</tr>";
                    }

                ?>
                </table>
                
                <?php } ?>
                
                <?php if($userType == "administrator"){
                    //Capturar todas las entradas realizadas
                    $usuarios = $connection -> execute_query("CALL getUsers()");
                ?>
                <hr>
                <h2>2. Usuarios</h2>
                <table>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Publicaciones</th>
                        <th>Inicios de sesión</th>
                    </tr>
                <?php

                    foreach($usuarios as $usuario){
                        if($usuario["username"] != $username){
                            echo "<tr>";
                            echo "<td>". $usuario["username"] ."</td>";
                            echo "<td>". $usuario["nickname"] ."</td>";
                            echo "<td>". $usuario["userType"] ."</td>";
                            echo "<td>". $usuario["publications"] ."</td>";
                            echo "<td>". $usuario["timesLogged"] ."</td>";
                            echo "</tr>";
                        }
                    }

                ?>
                </table>
                


           
        </div>
