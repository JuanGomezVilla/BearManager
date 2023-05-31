<?php

/**
 * Repository
 *
 * Represents a data repository
 * @author JuanGV
 * @version 1.0.0 
 */
class Repository {
    
    /**
     * @var ContextDB Database connection
     */
    protected ContextDB $connection;

    /**
     * Creates a new instance of the class and establish the connection to the database
     */
    public function __construct(){
        require("./config.php");
        $database = $config["database"];
        $this->connection = new ContextDB($database["host"], $database["user"], $database["password"]);
    }

    /**
     * Check if there is a connection to the database
     * @return bool True if the connection is active, false otherwise
     */
    public function isDatabaseConnection(){
        //Takes the connection and returns the status with the database
        return $this->connection->is_database_connection();
    }
}

?>