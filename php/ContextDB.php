<?php

/**
 * Class to manage the connection with the database and automate the processes
 * @author JuanGV
 * @version 1.0.0.0
 * @method array execute_query()
 * @method bool is_database_connection()
 * @method void finish()
 * @method object get_connection()
 */
class ContextDB {
    /**
     * @var connection Store the connection with the database
     */
    private $connection;

    /**
     * Create the object and establish a connection to the server.
     * The user must provide the access data, and optional the character in host
     * @param host Server data and database name
     * @param user Database username, default empty string
     * @param password Database user password, default empty string
     */
    function __construct($host, $user = "", $password = ""){
        //Try to create a connection, handling possible exceptions
        try {
            //Start the connection with the data passed by parameter
            $this -> connection = new PDO($host, $user, $password);
            //Set the error mode
            $this -> connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $error){
            //The connection will receive a null value
            $this -> connection = null;
        }
    }

    /**
     * Executes a query with possible arguments and the type of data fetch
     * @param string $text Query content
     * @param array $arguments Arguments Dictionary with query arguments, default null
     * @param int $type_fetch Type fetch data, by default with associated key
     * @return array It can return a null value, an array, or another type of value
     */
    function execute_query($text, $arguments = null, $type_fetch = PDO::FETCH_ASSOC){
        //If there is a connection to the database
        if($this -> connection != null){
            //Try to run the query
            try {
                //Create the query with the passed text
                $query = $this -> connection -> prepare($text);
                //Executes the query with possible arguments
                $query -> execute($arguments);
                //Returns the data according to the fetch type
                return $query -> fetchAll($type_fetch);
            } catch(Exception $error){
                //Returns null on error
                return null;
            }
        }
        //Returns null by default
        return null;
    }

    /**
     * Returns a boolean indicating whether there is a connection to the database
     * @return bool Mention if there is a connection to the database
     */
    public function is_database_connection(){
        //Returns if different from null
        return $this -> connection != null;
    }

    /**
     * End the connection
     */
    public function finish(){
        //Sets the value to null, that is, it empties the variable
        $this -> connection = null;
    }

    /**
     * Get the connection with the database
     * @return object Returns the connection
     */
    public function get_connection(){
        //Returns the connection
        return $this -> connection;
    }
}

?>