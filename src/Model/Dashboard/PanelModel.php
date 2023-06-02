<?php

class PanelModel {

    public static function get_response(){
        try {
            $repository = new PanelRepository();
            if(!$repository->isDatabaseConnection()) throw new Error("caca", 500);


            $settings = $repository->getSettings();
            $dataUser = $repository->getUserData($_SESSION["username"]);

            //Separate the data into variables from the previous query
            $userType = $dataUser["userType"];  //Type of user
            $username = $dataUser["username"];  //Username
            $nickname = $dataUser["nickname"];  //Name of individual
            $language = $dataUser["language"];  //Language
            $timesLogged = $dataUser["timesLogged"]; //Times logged in

            //If you have never logged in, there is a value for the password, and a password to confirm in the POST
            if($timesLogged == 0 && Utils::in_POST("newpassword") && Utils::in_POST("confirmpassword")){
                //TODO: contains spaces
                //Captures passwords, checks that at least one of them is not an empty string or contains spaces, and encrypts them directly
                $newPassword = Utils::get_from_POST("newpassword") != "" ? md5(Utils::get_from_POST("newpassword")) : null;
                $confirmPassword =  md5(Utils::get_from_POST("confirmpassword"));

                //If the passwords to be changed are not different and there is data for the captured user
                if($newPassword == $confirmPassword && $dataUser){
                    //Update the password in the user database
                    $repository -> updatePassword($newPassword, $username);

                    //Increases by 1 the number of times the user has logged in to avoid repeat password changes
                    $repository -> incrementTimesLogged($username);
                }

                //Regardless of the result, it redirects to the panel
                Utils::redirect("/panel");
            }

            $entradasRealizadas = $postsStudent = null;
            if($userType == "administrator" || $userType == "teacher"){
                $entradasRealizadas = $repository->getPublications();
            }

            if($userType == "student"){
                $postsStudent = $repository -> getPublicationsStudent($username);

                //Check if a student post exists to embed
                if(Utils::in_POST("title") && Utils::in_POST("content") && Utils::in_POST("image") && Utils::in_POST("observations")){
                    //Get the data passed to insert
                    $title          = Utils::get_from_POST("title");
                    $content        = Utils::get_from_POST("content");
                    $image          = Utils::get_from_POST("image");
                    $observations   = Utils::get_from_POST("observations");

                    //Comprobación de ataque XSS
                    if(Utils::is_html($title) || Utils::is_html($content) || Utils::is_html($observations)){
                        $repository -> createHistoryLog($username, "critical", "Intento de ataque XSS, usuario bloqueado");
                        $repository -> setEnabledStatusUser(0, $username);
                        Utils::redirect("/logout");
                    } else if(Utils::is_valid_url($image)){
                        //Insert the data into the database
                        $repository -> createPublication($username, $title, $image, $content);
                    }

                    //Redirect back to panel to avoid form resubmission
                    Utils::redirect("/panel");
                                       
                }
            }



            $users = $repository->getUsers();
            return ["code" => 200, "data" => [
                "settings" => $settings,
                "users" => $users,
                "userType" => $userType,
                "username" => $username,
                "nickname" => $nickname,
                "language" => $language,
                "timesLogged" => $timesLogged,
                "entradasRealizadas" => $entradasRealizadas,
                "postsStudent" => $postsStudent
            ]];
        } catch(Error $error){
            return ["code" => 500, "data" => null];
        }
    }
}

?>