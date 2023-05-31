<?php

//Starts a session
session_start();

//Check if a session exists, in that case it sends directly to the control panel
if(!Utils::in_SESSION("username")) Utils::redirect("/");

//Import the repository and the model
require("./Repository/Dashboard/SettingsRepository.php");
require("./Model/Dashboard/SettingsModel.php");
require("./languages.php");

//Creates the settings repository and gets the response
$repository = new SettingsRepository();
$response = SettingsModel::get_response();

//If the response code is 200
if($response["code"] == 200){
    $data = $response["data"];
    $texts = $languages[$data["language"]];
    require("View/Dashboard/SettingsView.php");
} else {
    //Error page
    require("View/Error/ErrorView.php");
}

?>