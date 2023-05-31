<?php

/**
 * Class to handle with simple APIs. You can specify the type
 * of call to be made, return JSON content, pick it up, and
 * process it
 * @author JuanGV
 * @version 1.0.0
 * @method mixed get_json_body()
 * @method mixed return_json()
 */
class QuickAPI {
    /**
     * Constructor for the class.
     * @param string $request_method The HTTP request method to be validated. Defaults to "GET"
     * @return void
     */
    function __construct($request_method = "GET", $access_control_allow_origin = false){
        //Sets the JSON header
        header("Content-Type: application/json; charset=utf-8");
        if($access_control_allow_origin) header("Access-Control-Allow-Origin: *");

        //Validate the request method
        if($_SERVER['REQUEST_METHOD'] !== $request_method){
            //Returns an error code
            http_response_code(500);
            exit();
        }
    }

    /**
     * Gets the JSON body passed directly to the API and returns it as an array
     * @return array It can return a null value, an array, or another type of value
     */
    function get_json_body(){
        //Gets the content of the input, and decodes it
        return json_decode(file_get_contents("php://input"), true);
    }

    /**
     * Generate and return a JSON response
     * @param mixed $data The data to be encoded as JSON
     * @param bool $pretty_print Whether to format the JSON output with indentation and line breaks. Defaults to false
     * @return void
     */
    function return_json($data, $pretty_print = false){
        //Encode the data as JSON with pretty printing if trigger is enabled
        if($pretty_print) echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        else echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

?>