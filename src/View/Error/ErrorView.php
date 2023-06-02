<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Title and icon -->
        <title>Error - <?php echo $config["title"]; ?></title>
        <link rel="icon" href="/assets/icon.ico">
        <meta charset="UTF-8" />

        <!-- Settings -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="google" content="notranslate" />
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>

        <!-- Styles -->
        <link rel="stylesheet" href="/css/error.css">
    </head>
    <body>
        <div class="wrap">
            <h1><?php echo $errorCode ?? 0; ?></h1>
        </div>
    </body>
</html>