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
        <meta name="google" content="notranslate" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/login.css">
    </head>
    <body>
        <!-- Centered container -->
        <div class="centered-container">
        <form method="POST">
                <p style="margin-top:0;font-size:25px">Login</p>
                <input class="form-row" type="text" name="username" placeholder="&#128100; User..." <?php 
                    if(Utils::in_SESSION("error")) echo "value=\"" .$_SESSION["error"] ."\"";
                ?> required>
                <input class="form-row" type="password" name="password" placeholder="&#128274; Password..." required>
                <button>Login</button>
            <?php //If there is an error in the session, shows an error message
                if(Utils::in_SESSION("error")) {
                ?>
                <p class="error-color" style="margin-bottom: 0;margin-top:23px;">Incorrect data</p>
        <?php } ?></form>
    </body>
</html>
<?php

//Empty the possible error value and the username of the session
session_unset();

?>