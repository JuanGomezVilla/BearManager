<?php

//Starts a session
session_start();

//Check if there is no session, in that case redirect to login
if(!Utils::in_SESSION("username")) Utils::redirect("/");

//Import the languages, the repository and the model
require("./languages/languages.php");
require("./Repository/Dashboard/FaqRepository.php");
require("./Model/Dashboard/FaqModel.php");

//Create an object for the repository and get the response from the model
$repository = new FaqRepository();
$response = FaqModel::get_response();

//If the response code is 200
if($response["code"] == 200){
    //Save the response data
    $data = $response["data"];

    //Save the texts according to the user language
    $texts = $languages[$data["language"]];

    //Import FAQ view
    require("View/Dashboard/FaqView.php");
} else {
    //Error page
    require("View/Error/ErrorView.php");
}

?>