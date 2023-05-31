<?php

//Import the repository, the model, and the quick api
require("../Repository/API/StatisticsRepository.php");
require("../Model/API/StatisticsModel.php");
require("../Utils/QuickAPI.php");

//Getting the response
$response = StatisticsModel::get_response();

//If the response code is 200
if($response["code"] == 200){
    //TODO
    $data = $response["data"];
    $quickApi = new QuickAPI("GET", true);
    $quickApi -> return_json($data);
} else {
    //Error page
    require("View/ErrorView.php");
}

?>