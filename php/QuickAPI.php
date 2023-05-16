<?php

class QuickAPI {

    function __construct($request_method = "GET"){
        header("Content-Type: application/json; charset=utf-8");
        if ($_SERVER['REQUEST_METHOD'] !== $request_method) {
            http_response_code(500);
            exit();
        }

    }

    function get_json_body(){
        return json_decode(file_get_contents("php://input"), true);
    }

    function return_json($data, $pretty_print = false){
        if($pretty_print) echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        else echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}


?>