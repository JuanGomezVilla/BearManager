<?php

class FaqModel {

    public static function get_response(){
        try {
            $repository = new FaqRepository();
            if(!$repository->isDatabaseConnection()) throw new Error("caca", 500);


            $settings = $repository->getSettings();
            $dataUser = $repository->getUserData($_SESSION["username"]);

            //Separate the data into variables from the previous query
            $userType = $dataUser["userType"];  //Type of user
            $username = $dataUser["username"];  //Username
            $nickname = $dataUser["nickname"];  //Name of individual
            $language = $dataUser["language"];  //Language
            $timesLogged = $dataUser["timesLogged"]; //Times logged in

            return ["code" => 200, "data" => [
                "settings" => $settings,
                "userType" => $userType,
                "username" => $username,
                "nickname" => $nickname,
                "language" => $language,
                "timesLogged" => $timesLogged,
            ]];
        } catch(Error $error){
            return ["code" => 500, "data" => null];
        }
    }
}

?>