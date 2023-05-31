<?php

class SettingsRepository extends Repository {
    /**
     * Change a user language
     * @param string $language Language to switch
     * @param string $username User whose language is changed
     */
    public function updateLanguage($language, $username){
        //Executes a query but does not return the result, because it is an update
        $this->connection->execute_query(
            "UPDATE users SET language = :language WHERE username = :username",
            [":language" => $language, ":username" => $username]
        );
    }

    /**
     * Update a user password
     * @param string $password New password
     * @param string $username User whose password is changed
     */
    public function updatePassword($password, $username){
        //Executes a query but does not return the result, because it is an update
        $this->connection->execute_query(
            "UPDATE users SET password = :password WHERE username = :username",
            [":password" => $password, ":username" => $username]
        );
    }

    /**
     * Get user data
     * @param string $username User to get the data
     * @return mixed User data
     */
    public function getUserData($username){
        //Executes a query, and returns the result
        return $this->connection->execute_query(
            "SELECT username, nickname, language, userType, timesLogged
            FROM users WHERE username = :username LIMIT 1",
            [":username" => $username]
        )[0];
    }

    /**
     * Get current settings
     * @return mixed Settings
     */
    public function getSettings(){
        return $this->connection->execute_query("SELECT * FROM settings", null, PDO::FETCH_KEY_PAIR);
    }


    public function getUserSimple($username, $currentPassword){
        return $this -> connection -> execute_query(
            "SELECT username FROM users WHERE username = :username AND password = :password LIMIT 1",
            array(":username" => $username, ":password" => $currentPassword)
        );
    }


    

    
}

?>