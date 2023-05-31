<?php

//Starts a session
session_start();

//Check if a session exists, in that case it sends directly to the control panel
if(Utils::in_SESSION("username")) Utils::redirect("/panel");

//Import the repository and the model
require("./Repository/Index/IndexRepository.php");
require("./Model/Index/IndexModel.php");

//Getting the response from the model
$model = new IndexModel();
$response = $model->get_response();

//If the response code is 200
if($response["code"] == 200){
    //TODO
    $data = $response["data"];
    require("View/Index/IndexView.php");
} else {
    //Error page
    require("View/Error/ErrorView.php");
}

?>