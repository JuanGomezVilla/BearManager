<?php





//Starts a session
session_start();

//Check if there is no session, in that case redirect to login
if(!Utils::in_SESSION("username")) Utils::redirect("/");

//Import the languages, the repository and the model
require("./languages/languages.php");
require("./Repository/Dashboard/SettingsRepository.php");
require("./Model/Dashboard/SettingsModel.php");

//Create an object for the repository and get the response from the model
$repository = new SettingsRepository();
$response = SettingsModel::get_response();

//If the response code is 200
if($response["code"] == 200 /*&& $_SERVER["REQUEST_URI"] == "/settings"*/){
    //Save the response data
    $data = $response["data"];

    //Save the texts according to the user language
    $texts = $languages[$data["language"]];

    //Import Settings view
    require("View/Dashboard/SettingsView.php");
} else {
    //ERROR CODE
    $errorCode = 404;

    //Error page
    require("View/Error/ErrorView.php");
}

?>