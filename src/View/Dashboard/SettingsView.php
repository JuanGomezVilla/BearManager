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
        <meta name="google" content="notranslate" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">

    </head>
    <body>

        <!-- Navigation bar -->
        <nav class="navbar">
            <div class="nav-item" onclick="location.href='/panel'"><?php echo $texts["home"]; ?></div>
            <div class="nav-item"><?php echo $texts["settings"]; ?></div>
            <div class="nav-item" onclick="location.href='/faq'">FAQ</div>
            <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
        </nav>

        <!-- Header title -->
        <div class="header">
            <h1><?php echo $texts["settings"]; ?></h1>
            <p><?php echo $texts["description_settings"]; ?></p>
        </div>

        <!-- Main container -->
        <div class="container">
            
            <!-- Change language -->
            <h2><?php echo $texts["change_language"] ?></h2>
            <form method="POST" style="display:inline-flex">
                <div class="form-row">
                    <select name="language">
                        <?php

                        $language = $data["language"];

                        //Write the selected language option first
                        echo "<option value=\"$language\" selected>". $languages[$language]["language"] ."</option>\n";

                        //For each existing language
                        foreach($data["languagesKeys"] as $languageLoop){
                            //If the language is different from the one used, write the option, to avoid repetitions
                            if($languageLoop != $language) echo str_repeat(" ", 24) ."<option value=\"$languageLoop\">". $languages[$languageLoop]["language"] ."</option>\n";
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
                    <input type="password" name="currentpassword" placeholder="<?php echo $texts["current_password"] ?>..." required/>
                    <input type="password" name="newpassword" placeholder="<?php echo $texts["new_password"] ?>..." required/>
                    <input type="password" name="confirmpassword" placeholder="<?php echo $texts["confirm_password"] ?>..." required/>
                    <button class="simple-button"><?php echo $texts["save"] ?></button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <footer>
            <div class="footer-links">
                <p class="footer-link"><?php echo $texts["home"]; ?></p>
                <p class="footer-link"><?php echo $texts["settings"]; ?></p>
                <p class="footer-link">FAQ</p>
            </div>
            <p class="footer-copyright">&copy; <?php echo date("Y") ." ". $config["author"]; ?></p>
        </footer>
    </body>
</html>