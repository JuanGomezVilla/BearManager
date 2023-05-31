<?php

/**
 * @author JuanGV
 * @version 1.0.0
 */
class IndexRepository extends Repository {
    /**
     * Returns the data of a user (username, nickname, user type, languages, and times logged)
     * @param string $username Username
     * @param string $password Password, pre-encrypted in MD5
     * @return mixed User data
     */
    public function getUserData($username, $password){
        //Get user limited to 1, to avoid data overload
        return $this->connection->execute_query("SELECT username, nickname, userType, language, timesLogged
            FROM users WHERE username = :username AND password = :password LIMIT 1",
            [":username" => $username, ":password" => $password]
        );
    }

    /**
     * Increases by 1 the number of times the user has logged in
     * @param string $username Username
     */
    public function incrementTimesLogged($username){
        //Increases by 1 the number of times you have logged in with a stored procedure in the database
        $this->connection->execute_query("CALL incrementTimesLogged(:username)", [":username" => $username]);
    }
}

?>