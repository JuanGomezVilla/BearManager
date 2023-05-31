<?php

class StatisticsModel {

    public static function get_response(){
        try {
            $repository = new StatisticsRepository(); 

            //Verifica la conexión con la base de datos
            if(!$repository->isDatabaseConnection()) throw new Error("There is no connection with the database", 500);

            //Captura de datos
            $estadisticas = $repository->getStatistics();

            return ["code" => 200, "data" => ["statistics" => $estadisticas]];
        } catch(Error $error){
            return ["code" => 400, "data" => null];
        }
    }
}

?>