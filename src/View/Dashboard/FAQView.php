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
        <link rel="stylesheet" href="/css/faq.css">
        
        <!-- Scripts -->
        <script src="/js/main.js"></script>
        <script src="/js/panel.js"></script>
    </head>
    <body>
        <!-- Navigation bar -->
        <nav class="navbar" id="navbar">
            <div class="nav-header">
                <div class="nav-item logo"></div>
                <div class="nav-item icon" id="icon"></div>
            </div>
            <div class="nav-items">
                <div class="nav-item" onclick="location.href='/'"><?php echo $texts["home"]; ?></div>
                <div class="nav-item" onclick="location.href='/settings'"><?php echo $texts["settings"]; ?></div>
                <div class="nav-item active">FAQ</div>
                <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
            </div>
        </nav>

        <!-- Title and description -->
        <div class="header">
            <h1>Frequent Asked Questions</h1>
            <p class="header-text"><?php echo $texts["description_faq"]; ?></p>
        </div>

        <!-- Main container -->
        <div class="container">
            <div class="faq-question">
                <h2><?php echo $texts["faq_question_1"]; ?></h2>
                <p><?php echo $texts["faq_answer_1"]; ?></p>
            </div>
            <hr>
            <div class="faq-question">
                <h2><?php echo $texts["faq_question_2"]; ?></h2>
                <p><?php echo $texts["faq_answer_2"]; ?></p>
            </div>
            <hr>
            <div class="faq-question">
                <h2><?php echo $texts["faq_question_3"]; ?></h2>
                <p><?php echo $texts["faq_answer_3"]; ?></p>
            </div>
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