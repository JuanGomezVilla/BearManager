<?php

class FaqRepository extends Repository {

    public function getSettings(){
        return $this->connection->execute_query("SELECT * FROM settings", null, PDO::FETCH_KEY_PAIR);
    }

    public function getUserData($username){
        return $this->connection -> execute_query(
            "SELECT username, nickname, language, userType, timesLogged FROM users WHERE username = :username LIMIT 1",
            array(":username" => $username)
        )[0];
    }
}

?>