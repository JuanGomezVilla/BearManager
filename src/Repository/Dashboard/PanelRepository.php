<?php

class PanelRepository extends Repository {

    public function getUsers(){
        return $this->connection->execute_query("SELECT * FROM users");
    }

    public function getSettings(){
        return $this->connection->execute_query("SELECT * FROM settings", null, PDO::FETCH_KEY_PAIR);
    }

    public function getUserData($username){
        return $this->connection -> execute_query(
            "SELECT username, nickname, language, userType, timesLogged FROM users WHERE username = :username LIMIT 1",
            array(":username" => $username)
        )[0];
    }

    public function updatePassword($newPassword, $username){
        $this->connection -> execute_query(
            "UPDATE users SET password = :password WHERE username = :username",
            array(":password" => $newPassword, ":username" => $username)
        );
    }

    public function incrementTimesLogged($username){
        $this->connection -> execute_query("CALL incrementTimesLogged(:username)", array(":username" => $username));
    }
}

?>