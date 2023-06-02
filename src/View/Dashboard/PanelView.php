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

        <!-- Scripts -->
        <script src="/js/main.js"></script>
        <script src="/js/panel.js"></script>
    </head>
    <body>
        <?php if($data["timesLogged"] == 0){ ?>
        
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
        <?php } ?>

        <!-- Navigation bar -->
        <nav class="navbar" id="navbar">
            <div class="nav-header">
                <div class="nav-item logo"></div>
                <div class="nav-item icon" id="icon"></div>
            </div>
            <div class="nav-items">
                <div class="nav-item active"><?php echo $texts["home"]; ?></div>
                <div class="nav-item" onclick="location.href='/settings'"><?php echo $texts["settings"]; ?></div>
                <div class="nav-item" onclick="location.href='/faq'">FAQ</div>
                <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
            </div>
        </nav>

        <!-- Title and description -->
        <div class="header">
            <h1><?php echo $texts["welcome"] .", ". $data["nickname"]; ?></h1>
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
        
        <!-- Main container -->
        <div class="container">
            <?php if($userType == "administrator" || $userType == "teacher"){
                $entradasRealizadas = $data["entradasRealizadas"];
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

                <?php if($userType == "student"){
                    //Capture posts made by the student
                    $postsStudent = $data["postsStudent"];
                    $settings = $data["settings"];

                    //Maximum posts and posts remaining
                    $max_publications = (int) $settings["max_publications"];
                    $remaining_posts = $max_publications - count($postsStudent);
                
                ?><!-- Section 1 (Publications made) -->
                <h2><?php
                    //Print the title of the section, and the remaining number of entries if that value is different from 0
                    echo $texts["publications_made"];
                    if($remaining_posts != 0) echo " (". strtolower($texts["remaining"]) .": $remaining_posts)";
                ?></h2>
                <?php

                

                //If the number of posts is 0
                if(count($postsStudent) == 0){
                    //Mentions that the student has not created posts yet
                    echo "<p>". $texts["description_no_posts"] ."...</p>";
                } else {
                    //Table with tabulation and titles
                    echo "<table>\n";
                    echo str_repeat(" ", 16) ."<tr>\n";
                    echo str_repeat(" ", 20) ."<th>". $texts["title"] ."</th>\n";
                    echo str_repeat(" ", 20) ."<th>". $texts["accepted"] ."</th>\n";
                    echo str_repeat(" ", 20) ."<th>". $texts["image"] ."</th>\n";
                    echo str_repeat(" ", 20) ."<th>". $texts["content"] ."</th>\n";
                    echo str_repeat(" ", 16) ."</tr>\n";
                    
                    foreach($postsStudent as $post){
                        echo str_repeat(" ", 16) ."<tr>\n";
                        echo str_repeat(" ", 20) ."<td>". $post["title"] ."</td>\n";
                        echo str_repeat(" ", 20) ."<td>". ($post["accepted"] ? $texts["yes"] : $texts["no"]) ."</td>\n";
                        echo str_repeat(" ", 20) ."<td><a target=\"_blank\" href=\"". $post["image"] ."\">Ver imagen</a></td>\n";
                        echo str_repeat(" ", 20) ."<td>". $post["content"] ."</td>\n";
                        echo str_repeat(" ", 16) ."</tr>\n";
                    }
                    echo str_repeat(" ", 12) ."</table>";
                }

            ?>

            <p style="font-style:italic">*<?php echo $texts["description_accepted"]; ?></p>
            <hr><?php if(count($postsStudent) >= $max_publications) { ?>
            
            <p><?php echo $texts["description_max_posts"]; ?>...</p><?php } else { ?>

            <!-- Post creation form -->
            <h2><?php echo $texts["create_a_post"]; ?></h2>
            <script src="/js/inputBox.js"></script>
            <form method="POST">
                <div class="form-row">
                    <input id="inputTitle" name="title" type="text" placeholder="<?php echo $texts["title"]; ?>..." required>
                    <input id="inputImage" name="image" type="text" placeholder="<?php echo $texts["image"]; ?>..." required>
                    <input id="inputObservations" name="observations" type="text" placeholder="<?php echo $texts["observations"]; ?>...">
                </div>
                <div class="form-row">
                    <textarea id="inputContent" name="content" placeholder="<?php echo $texts["drafting"]; ?>..." style="height:200px" required></textarea>
                </div>
                <button id="buttonPublish" disabled><?php echo $texts["publish"]; ?></button>
            </form><?php } ?>

            <?php } ?>
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