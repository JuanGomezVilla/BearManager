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
        <link rel="stylesheet" href="/css/panel.css">

        <!-- Scripts -->
        <script src="/js/panel.js"></script>
    </head>
    <body>
        <!-- Navigation bar -->
        <nav class="navbar">
            <div class="nav-item">Home</div>
            <div class="nav-item" onclick="location.href='/settings'"><?php echo $texts["settings"]; ?></div>
            <div class="nav-item" onclick="location.href='/faq'">FAQ</div>
            <div class="nav-item" onclick="location.href='/logout'"><?php echo $texts["logout"]; ?></div>
        </nav>

        <!-- Title and description -->
        <div class="header">
            <h1><?php echo $texts["welcome"] .", $nickname"; ?></h1>
            <p class="header-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam imperdiet tortor a tellus consectetur, eget sodales enim euismod</p>
            <hr class="header-hr">
            <p><?php echo $texts["description_more_info"]; ?></p>
            <button class="simple-button" id="buttonMoreInfo"><?php echo $texts["more_information"]; ?></button>
        </div>

        <!-- Main container -->
        <div class="container">
            <?php if($userType == "administrator"){
                //Capturar todas las entradas realizadas
                $entradasRealizadas = $connection -> execute_query("SELECT * FROM publications");
                $usuarios = $connection -> execute_query("CALL getUsers()");
            ?>
                
                <h1>Panel de administración</h1>

                <h2>Entradas realizadas</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Contenido</th>
                        <th>Aceptado</th>
                        <th>Usuario</th>
                    </tr>
                <?php

                    foreach($entradasRealizadas as $entrada){
                        echo "<tr>";
                        echo "<td>". $entrada["id"] ."</td>";
                        echo "<td>". $entrada["title"] ."</td>";
                        echo "<td>". $entrada["content"] ."</td>";
                        echo "<td>". $entrada["accepted"] ? $texts["yes"] : $texts["no"] ."</td>";
                        echo "<td>". $entrada["student"] ."</td>";
                        echo "</tr>";
                    }

                ?>
                </table>
                
                <hr>

                <h2>Usuarios</h2>
                <table>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Publicaciones</th>
                    </tr>
                <?php

                    foreach($usuarios as $usuario){
                        echo "<tr>";
                        echo "<td>". $usuario["username"] ."</td>";
                        echo "<td>". $usuario["nickname"] ."</td>";
                        echo "<td>". $usuario["userType"] ."</td>";
                        echo "<td>". $usuario["publications"] ."</td>";
                        echo "</tr>";
                    }

                ?>
                </table>
                


            <?php } else if($userType == "student"){
                //Capture posts made by the student
                $postsStudent = $connection -> execute_query(
                    "SELECT title, accepted, image, content FROM publications WHERE student = :student",
                    array(":student" => $username)
                );

                //Maximum posts and posts remaining
                $max_publications = (int) $settings["max_publications"];
                $remaining_posts = $max_publications - count($postsStudent);
            
            ?><!-- Section 1 (Publications made) -->
            <h2><?php
                //Print the title of the section, and the remaining number of entries if that value is different from 0
                echo $texts["publications_made"];
                if($remaining_posts != 0) echo " (". $texts["remaining"] .": $remaining_posts)";
            ?></h2>
            <?php

                //Check if a student post exists to embed
                if(UtilsWeb::in_POST("title") && UtilsWeb::in_POST("content")){
                    //Get the data passed to insert
                    $title = UtilsWeb::get_from_POST("title");
                    $content = UtilsWeb::get_from_POST("content");

                    //Insert the data into the database
                    $connection -> execute_query(
                        "CALL createPublication(:student, :title, 0, :image, :content)",
                        array(":student" => $username, ":title" => $title, ":image" => "no", ":content" => $content)
                    );

                    //Redirect back to panel to avoid form resubmission
                    UtilsWeb::redirect("/panel");
                }

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
                        echo str_repeat(" ", 20) ."<td>". $post["image"] ."</td>\n";
                        echo str_repeat(" ", 20) ."<td>". $post["content"] ."</td>\n";
                        echo str_repeat(" ", 16) ."</tr>\n";
                    }
                    echo str_repeat(" ", 12) ."</table>";
                }

            ?>

            <p style="font-style:italic">*<?php echo $texts["description_accepted"]; ?></p>
            <hr><?php if(count($postsStudent) >= $max_publications) { ?>
            
            <p>Has superado el número permitido (<?php echo $max_publications ?>) para crear entradas...</p><?php } else { ?>

            <!-- Formulario de creación de una publicación -->
            <h2><?php echo $texts["create_a_post"]; ?></h2>
            <form method="POST">
                <div class="form-row">
                    <input name="title" type="text" placeholder="<?php echo $texts["title"]; ?>..." required>
                    <input name="image" type="text" placeholder="<?php echo $texts["image"]; ?>..." required>
                    <input name="observations" type="text" placeholder="<?php echo $texts["observations"]; ?>...">
                </div>
                <div class="form-row">
                    <textarea name="content" placeholder="<?php echo $texts["drafting"]; ?>..." style="height:200px" required></textarea>
                </div>
                <button><?php echo $texts["publish"]; ?></button>
            </form><?php } ?>
            <?php } else if($userType == "teacher") {
                
                //Capturar entradas
                $entradas = $connection -> execute_query("SELECT * FROM publications");
                
                ?>
                
                <h2>Profesor</h2>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Image</th>
                        <th>Content</th>
                        <th>Accepted</th>
                        <th>Student</th>
                    </tr>
                <?php

                    foreach($entradas as $entrada){
                        echo "<tr>";
                        echo "<td>". $entrada["id"] ."</td>";
                        echo "<td>". $entrada["title"] ."</td>";
                        echo "<td>". $entrada["image"] ."</td>";
                        echo "<td>". $entrada["content"] ."</td>";
                        echo "<td>". $entrada["accepted"] ."</td>";
                        echo "<td>". $entrada["student"] ."</td>";
                        echo "</tr>";
                    }

                ?>
                </table>

                <!-- El profesor solo puede ver entradas, aceptarlas, y editarlas -->

                

            <?php } ?>

        </div>

        <!-- Footer -->
        <footer>
            <div class="footer-links">
                <p class="footer-link">Home</p>
                <p class="footer-link"><?php echo $texts["settings"]; ?></p>
                <p class="footer-link">FAQ</p>
            </div>
            <p class="footer-copyright">&copy; <?php echo date("Y") ." ". $config["author"]; ?></p>
        </footer>
    </body>
</html><?php

//Finish the connection with the database
$connection -> finish();

?>