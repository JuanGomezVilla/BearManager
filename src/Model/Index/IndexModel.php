<?php

class IndexModel {
    
    public static function get_response(){
        try {
            $repository = new IndexRepository();

            //Verifica la conexión con la base de datos
            if(!$repository->isDatabaseConnection()) throw new Error("There is no connection with the database", 500);

            if(Utils::in_POST("username") && Utils::in_POST("password")){
                //Verificaciones de seguridad

                $username = strtolower(Utils::get_from_POST("username")); 
                $passwordVerify = Utils::get_from_POST("password");
                $password = $passwordVerify != null && $passwordVerify != "" && !Utils::is_html($passwordVerify) /*&& !UtilsWeb::contains_spaces($passwordVerify)*/ ? md5($passwordVerify) : null;
                if($password != null && strlen($username) != 0 /*&& !UtilsWeb::contains_spaces($username)*/ && strlen($username) < 150){
                    $user = $repository->getUserData($username, $password);

                    //If data exists, it is because the user exists
                    if($user && $user["enabled"]){
                        //Sets the value of the username, the nickname and the user type
                        $_SESSION["username"] = $user["username"];

                        //Increase times logged
                        if($user["timesLogged"] != 0) $repository->incrementTimesLogged($username);

                        //Redirect to control panel
                        Utils::redirect("/panel");
                    } else {
                        //Username error message
                        $_SESSION["error"] = $username;
                    }
                }

                //Final redirect to avoid resubmission of the form with POST
                Utils::redirect("/");
            }

            return ["code" => 200, "data" => []];
        } catch(Error $error){
            return ["code" => 400, "data" => null];
        }
        
    }
}
?>
