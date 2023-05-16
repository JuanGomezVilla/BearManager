<?php

require("../php/QuickAPI.php");

$api = new QuickAPI("POST");


$api -> return_json(array("nombre" => "juan"));

?>