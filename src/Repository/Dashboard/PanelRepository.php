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

    public function getPublications(){
        return $this->connection -> execute_query("CALL getPublications()");
    }

    public function createPublication($username, $title, $image, $content){
        $this->connection->execute_query(
            "CALL createPublication(:student, :title, 0, :image, :content);",
            [
                ":student" => $username,
                ":title" => $title,
                ":image" => $image,
                ":content" => $content
            ]
        );
    }

    public function getPublicationsStudent($username){
        return $this->connection->execute_query(
            "SELECT title, accepted, image, content
            FROM publications 
            WHERE student = :student",
            array(":student" => $username)
        );
    }

    public function incrementTimesLogged($username){
        $this->connection -> execute_query("CALL incrementTimesLogged(:username)", array(":username" => $username));
    }

    public function createHistoryLog($username, $typeRecord, $description){
        $this->connection->execute_query(
            "CALL createHistoryLog(:username, :typeRecord, :description);",
            [
                ":username" => $username,
                ":typeRecord" => $typeRecord,
                ":description" => $description
            ]
        );
    }

    public function setEnabledStatusUser($enabled, $username){
        $this->connection->execute_query(
            "CALL setEnabledStatusUser(:enabled, :username);",
            [
                ":enabled" => $enabled,
                ":username" => $username
            ]
        );
    }
}

?>