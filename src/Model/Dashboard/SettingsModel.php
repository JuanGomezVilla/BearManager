<?php

class SettingsModel {

    public static function get_response(){
        try {
            $repository = new SettingsRepository();
            if(!$repository->isDatabaseConnection()) throw new Error("caca", 500);


            //Gets the settings from the database
            $settings = $repository -> getSettings();
            $dataUser = $repository -> getUserData($_SESSION["username"]);

            //Separate the data into variables from the previous query
            $userType = $dataUser["userType"];  //Type of user
            $username = $dataUser["username"];  //Username
            $nickname = $dataUser["nickname"];  //Name of individual
            $language = $dataUser["language"];  //Language
            $timesLogged = $dataUser["timesLogged"]; //Times logged in

            global $languages;

            
            //Obtiene los lenguajes
            $languagesKeys = array_keys($languages);

            //Cambiar el lenguaje
            if(Utils::in_POST("language")){
                $languageChange = Utils::get_from_POST("language");

                //Verificar que el lenguaje está dentro de los existentes
                if(in_array($languageChange, $languagesKeys)){//
                    $repository -> updateLanguage($languageChange, $username);
                    $_SESSION["language"] = $languageChange;
                }
                Utils::redirect("/settings");
            }

            //If there is a current password, a new password and confirmation
            if(Utils::in_POST("currentpassword") && Utils::in_POST("newpassword") && Utils::in_POST("confirmpassword")){
                //Capture the values ​​and encrypt them directly to MD5
                $currentPassword = md5(Utils::get_from_POST("currentpassword"));
                $confirmPassword = md5(Utils::get_from_POST("confirmpassword"));
                $newPassword = md5(Utils::get_from_POST("newpassword"));
                
                //Capture the user with that password
                $userCaptured = $repository->getUserSimple($username, $currentPassword);

                //If the passwords match and a user exists, update the password
                if($newPassword == $confirmPassword && $userCaptured) $repository->updatePassword($newPassword, $username);
                
                //Redirect back to settings panel
                Utils::redirect("/settings");
            }
    


            return ["code" => 200, "data" => [
                "userType" => $userType,
                "username" => $username,
                "nickname" => $nickname,
                "language" => $language,
                "timesLogged" => $timesLogged,
                "languagesKeys" => $languagesKeys
            ]];
        } catch(Error $error){
            return ["code" => 500, "data" => null];
        }
    }
}

?>