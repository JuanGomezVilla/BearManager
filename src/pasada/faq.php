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

if($connection -> is_database_connection()){

}

//Get the texts in the selected language
$texts = $languages[$language];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Title and icon -->
        <title>FAQ - <?php echo $config["title"] ?></title>
        <link rel="icon" href="/assets/icon.ico">
        <meta charset="UTF-8" />

        <!-- Settings -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">


    </head>
    <body>
        

        <!-- Title and description -->
        <div class="header">
            <h1>Frequent Asked Questions</h1>
            <p class="header-text">lkhkljasljfhsadh</p>
            <hr class="header-hr">
            <p><?php echo $texts["description_more_info"]; ?></p>
            <button class="simple-button" id="buttonMoreInfo"><?php echo $texts["more_information"]; ?></button>
        </div>

        <!-- Main container -->
        <div class="container">
            <style>

                .pregunta {
                    margin-bottom: 30px;
                    margin-top: 30px;
                }

                .pregunta p, .pregunta h2 {
                    margin-top: 0;
                    margin-bottom: 0;
                }

            </style>
            <div class="pregunta">
                <h2>¿Qué pasa si olvido mi contraseña?</h2>
                <p>En caso de perder tu contraseña, habla con el administrador para restablecer los datos
                </p>
            </div>
            <hr>
            <div class="pregunta">
                <h2>¿Puedo borrar una publicación?</h2>
                <p>No, no puedes borrar una publicación que has escrito. Esta acción solo la puede hacer un
                    profesor o un administrador.
                </p>
            </div>
            <hr>
            <div class="pregunta">
                <h2>Pregunta 1</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ullamcorper nulla sed
                    volutpat interdum. Quisque vitae tellus id magna porttitor posuere nec condimentum
                    sem. Etiam nisi dui, suscipit bibendum consequat nec, luctus vel justo.</p>
            </div>
            <hr>
            <div class="pregunta">
                <h2>Pregunta 1</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ullamcorper nulla sed
                    volutpat interdum. Quisque vitae tellus id magna porttitor posuere nec condimentum
                    sem. Etiam nisi dui, suscipit bibendum consequat nec, luctus vel justo.</p>
            </div>
        </div>
    </body>
</html>