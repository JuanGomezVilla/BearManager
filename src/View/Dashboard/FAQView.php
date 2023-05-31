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
        <meta name="google" content="notranslate" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/main.css">
        
        <!-- Scripts -->
        <script src="/js/panel.js"></script>
    </head>
    <body>
        <!-- Navigation bar -->
        <nav class="navbar">
            <div class="nav-item" onclick="location.href='/'"><?php echo $texts["home"]; ?></div>
            <div class="nav-item" onclick="location.href='/settings'"><?php echo $texts["settings"]; ?></div>
            <div class="nav-item">FAQ</div>
            <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
        </nav>
    </body>
</html>