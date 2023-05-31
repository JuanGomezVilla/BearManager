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
        <meta name="google" content="notranslate" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
        <!-- Navigation bar -->
        <nav class="navbar">
            <div class="nav-item"><?php echo $texts["home"]; ?></div>
            <div class="nav-item" onclick="location.href='/settings'"><?php echo $texts["settings"]; ?></div>
            <div class="nav-item" onclick="location.href='/faq'">FAQ</div>
            <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
        </nav>

        <!-- Title and description -->
        <div class="header">
            <h1><?php echo $texts["welcome"] .", "//. $data["nickname"]; ?></h1>
            <p class="header-text"><?php
            $userType = $data["userType"];
                if($userType == "administrator") echo $texts["description_administrator"];
                else if($userType == "teacher") echo $texts["description_teacher"];
                else echo $texts["description_student"];
            ?></p>
            <hr class="header-hr">
            <p><?php echo $texts["description_more_info"]; ?></p>
            <button class="simple-button" id="buttonMoreInfo"><?php echo $texts["more_information"]; ?></button>
        </div>
        <?php
            $users = $data["users"];
            foreach($users as $user){
                echo "<p>". $user["username"] ."</p>";
            }
        ?>
    </body>
</html>