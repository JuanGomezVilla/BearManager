<?php

//Starts a session
session_start();

//Check if a session exists, in that case it sends directly to the control panel
if(!Utils::in_SESSION("username")) Utils::redirect("/");

//Import the repository and the model
require("./languages.php");
require("./Repository/Dashboard/PanelRepository.php");
require("./Model/Dashboard/PanelModel.php");

//Getting the response from the model
$repository = new PanelRepository();
$response = PanelModel::get_response();

//If the response code is 200
if($response["code"] == 200){
    //TODO
    $data = $response["data"];
    $texts = $languages["en"];
    require("View/Dashboard/PanelView.php");
} else {
    //Error page
    require("View/Error/ErrorView.php");
}

?>