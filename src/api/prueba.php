<html><head><title>Testing</title></head><body><pre><?php


$path = $_GET['path'];
$params = explode("/", $path); //Parametros despues de prueba
$site = array_shift($params); //prueba

var_dump($params);

?></pre></body></html>