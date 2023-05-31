<?php

//Obtiene la clase de Repository
require("../Repository/Repository.php");

class StatisticsRepository extends Repository {
    public function __construct()
    {
        parent::__construct();
    }

    public function getStatistics(){
        return $this->connection->execute_query("call getStatistics();");
    }
}

?>