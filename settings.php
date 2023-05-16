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

//Gets the settings from the database
$settings = $connection -> execute_query("SELECT * FROM settings", null, PDO::FETCH_KEY_PAIR);

//Get user data from session
$userType = $_SESSION["userType"];
$username = $_SESSION["username"];
$nickname = $_SESSION["nickname"];
$language = $_SESSION["language"];

//Texts in the selected language
$texts = $languages[$language];

//Obtiene los lenguajes
$languagesKeys = array_keys($languages);

//Cambiar el lenguaje
if(UtilsWeb::in_POST("language")){
    $languageChange = UtilsWeb::get_from_POST("language");

    //Verificar que el lenguaje está dentro de los existentes
    if(in_array($languageChange, $languagesKeys)){
        $connection -> execute_query(
            "UPDATE users SET language = :language WHERE username = :username",
            array(":language" => $languageChange, ":username" => $username)
        );
        $_SESSION["language"] = $languageChange;
    }
    UtilsWeb::redirect("/settings");
}

//Cambiar la contraseña
if(UtilsWeb::in_POST("currentpassword") && UtilsWeb::in_POST("newpassword") && UtilsWeb::in_POST("confirmpassword")){
    //Capturar los valores y cifrarlos directamente
    $currentPassword = md5(UtilsWeb::get_from_POST("currentpassword"));
    $newPassword = md5(UtilsWeb::get_from_POST("newpassword"));
    $confirmPassword = md5(UtilsWeb::get_from_POST("confirmpassword"));

    //Capturar el usuario con esa contraseña
    $userCaptured = $connection -> execute_query(
        "SELECT username FROM users WHERE username = :username AND password = :password LIMIT 1",
        array(":username" => $username, ":password" => $currentPassword)
    );

    //Si las contraseñas a cambiar no son diferentes y existen datos para el usuario capturado
    if($newPassword == $confirmPassword && $userCaptured){
        //Actualiza la contraseña
        $connection -> execute_query(
            "UPDATE users SET password = :password WHERE username = :username",
            array(":password" => $newPassword, ":username" => $username)
        );
    }
    UtilsWeb::redirect("/settings");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Title and icon -->
        <title><?php echo $texts["settings"] ." - ". $config["title"] ?></title>
        <link rel="icon" href="/assets/icon.ico">
        <meta charset="UTF-8" />

        <!-- Settings -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/settings.css">
    </head>
    <body>
        <!-- Navigation bar -->
        <nav class="navbar">
            <div class="nav-item" onclick="location.href='/'">Home</div>
            <div class="nav-item"><?php echo $texts["settings"]; ?></div>
            <div class="nav-item" onclick="location.href='/faq'">FAQ</div>
            <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
        </nav>

        <!-- HEADER TITLE -->
        <div class="header">
            <h1><?php echo $texts["settings"]; ?></h1>
            <p><?php echo $texts["description_settings"]; ?></p>
        </div>


        <div class="container">
            <!-- Change language -->
            <h2><?php echo $texts["change_language"] ?></h2>
            <form method="POST" style="display:inline-flex">
                <div class="form-row">
                <select name="language">
                    <?php
                        //Write the selected language option first
                        echo "<option value=\"$language\" selected>". $languages[$language]["language"] ."</option>";

                        //For each existing language
                        foreach($languagesKeys as $languageLoop){
                            //If the language is different from the one used, write the option, to avoid repetitions
                            if($languageLoop != $language) echo "<option value=\"$languageLoop\">". $languages[$languageLoop]["language"] ."</option>";
                        }
                    ?>
                </select>
                <button class="simple-button"><?php echo $texts["save"] ?></button>
                </div>
            </form>

            <hr>
            
            <!-- Change password -->
            <h2><?php echo $texts["change_password"] ?></h2>
            <form method="POST">
            <div class="form-row">
                <input type="password" name="currentpassword" placeholder="<?php echo $texts["current_password"] ?>" required/>
                <input type="password" name="newpassword" placeholder="<?php echo $texts["new_password"] ?>" required/>
                <input type="password" name="confirmpassword" placeholder="<?php echo $texts["confirm_password"] ?>" required/>
                <button class="simple-button"><?php echo $texts["save"] ?></button>
            </div>
            </form>
        </div>

        <!-- Footer -->
        <footer>

        </footer>
    </body>
</html><?php

//Finish the connection with the database
$connection -> finish();

?>