<?php

/**
 * Class containing useful methods
 * @author JuanGV
 * @version 1.0.0
 * @method void set_json_header()
 * @method string clear_text()
 * @method bool contains_spaces()
 * @method bool is_lowercase()
 * @method bool is_html()
 * @method bool is_email()
 * @method bool have_only_numbers_and_letters()
 * @method void print_json()
 * @method void redirect()
 * @method bool in_GET()
 * @method bool in_POST()
 * @method bool in_SESSION()
 * @method mixed get_from_GET()
 * @method mixed get_from_POST()
 * @method array get_json_from_POST()
 * @method bool key_exists_in_array()
 * @method mixed get_value_from_array_by_key()
 */
class Utils {
    /**
     * Set a JSON content header to display it that way
     */
    public static function set_json_header(){
        //Set the header
        header("Content-type: application/json");
    }

    /**
     * Cleans a text passed by parameter, removing special characters, etc.
     * @param string $text Text content
     * @return array Cleaned up text
     */
    public static function clear_text($text){
        //Remove spaces, escape HTML tags, remove slashes from a string with escaped quotes
        return htmlspecialchars(stripslashes(trim($text)));
    }

    /**
     * Method that returns true if a text contains spaces
     * @param string $text Text content
     * @return bool True if contains spaces
     */
    public static function contains_spaces($text){
        //Method to check if a string contains spaces
        return str_contains($text, " ");
    }

    /**
     * Method that returns true if a text is in lowercase
     * @param string $text Text content
     * @return bool True if is in lowercase
     */
    public static function is_lowercase($text){
        //The text is converted to lowercase and compared to the original
        return strtolower($text) == $text;
    }

    /**
     * Function to check if a url complies with the valid format
     * @param string $url Value to check
     * @return bool True if is valid
     */
    public static function is_valid_url($url){
        //Use a filter function and a regular expression
        return (bool) filter_var($url, FILTER_VALIDATE_URL) && preg_match("/^(https?|http):\/\/([A-Z0-9-]+\.)+[A-Z]{2,6}\/?/i", $url);
    }

    /**
     * Method that returns true if a text contains HTML tags
     * @param string $text Text content
     * @return bool True if contains HTML tags
     */
    public static function is_html($text){
        //Use (bool) on preg_match, return if is html
        return (bool) preg_match("/<\s?[^\>]*\/?\s?>/i", $text);
    }

    /**
     * Method that returns true if a text is a valid email
     * @param string $email Email text
     * @return bool True if is a valid email
     */
    public static function is_email($email){
        //If the returned text is different from null, it is because the email is valid
        return filter_var($email, FILTER_VALIDATE_EMAIL) != null ? true : false;
    }

    /**
     * Method that returns true if a text contains only letters and numbers
     * @param $text Text content
     * @return bool True if have only numbers and letters
     */
    public static function have_only_numbers_and_letters($text){
        //Regular expression to check if a text contains only numbers and letters
        return (bool) preg_match("/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/", $text);
    }

    /**
     * Method that prints a list or an object in JSON format
     * @param $content Object or array
     * @param $pretty_print Allows you to format the text to be written, default false
     */
    public static function print_json($content, $pretty_print = false){
        //Switch to format the JSON code to be printed
        if($pretty_print) echo json_encode($content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        else echo json_encode($content, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Redirect to a page directly
     * @param $location Landing page
     */
    public static function redirect($location){
        //Set the header and prevent further code from appearing (true, 302)
        header("Location: $location");
        exit();
    }

    /**
     * Method to check if a value with a key exists in the GET
     * @param $key Key in GET
     * @return bool Returns true if exists
     */
    public static function in_GET($key){
        //Returns true if the key is found in the GET
        return isset($_GET[$key]);
    }

    /**
     * Method to check if a value with a key exists in the POST
     * @param $key Key in POST
     * @return bool Returns true if exists
     */
    public static function in_POST($key){
        //Returns true if the key is found in the POST
        return isset($_POST[$key]);
    }

    /**
     * Method to check if a value with a key exists in the SESSION
     * @param $key Key in SESSION
     * @return bool Returns true if exists
     */
    public static function in_SESSION($key){
        //Returns true if the key is found in the SESSION
        return isset($_SESSION[$key]);
    }

    /**
     * Method that returns an object, if it does not exist in the GET, null is returned
     * @param $key Key in GET
     * @return mixed Value associated with the key in GET
     */
    public static function get_from_GET($key){
        //Checks if it exists and returns it, otherwise returns null
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    /**
     * Method that returns an object, if it does not exist in the POST, null is returned
     * @param $key Key in POST
     * @return mixed Value associated with the key in POST
     */
    public static function get_from_POST($key){
        //Checks if it exists and returns it, otherwise returns null
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    /**
     * Method that captures the JSON content passed by the POST
     * @return object Array as the JSON content
     */
    public static function get_json_from_POST(){
        //Translates the JSON code from the POST to an array and returns it
        return json_decode(file_get_contents("php://input"), true);
    }

    /**
     * Method to check if a key exists in an array, improved version
     * @param $key Key in array
     * @param $data Array to search key
     * @return bool Returns true if exists in array
     */
    public static function key_exists_in_array($key, $data){
        //If the data passed by parameter is null, check it against a null array to avoid errors
        return array_key_exists($key, $data == null ? array() : $data);
    }
    
    /**
     * Method to return a value if it exists in an array, null otherwise
     * @param $key Key in array
     * @param $data Array to search key
     * @return mixed Returns a value, null otherwise
     */
    public static function get_value_from_array_by_key($key, $data){
        //If the key exists in the array, return the value, else null
        return array_key_exists($key, $data) ? $data[$key] : null;
    }
}

?>