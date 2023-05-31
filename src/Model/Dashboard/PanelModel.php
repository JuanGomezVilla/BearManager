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



            $users = $repository->getUsers();
            return ["code" => 200, "data" => [
                "users" => $users,
                "userType" => $userType,
                "username" => $username,
                "nickname" => $nickname,
                "language" => $language,
                "timesLogged" => $timesLogged
            ]];
        } catch(Error $error){
            return ["code" => 500, "data" => null];
        }
    }
}

?>