<?php

//IMPORTACIÓN DE PLUGINS
require("../php/QuickAPI.php"); //Script para crear APIs
require("../php/ContextDB.php"); //Script para manejar con bases de datos
require("../php/UtilsWeb.php"); //Útiles de web

//Creación de la API, del tipo POST
$api = new QuickAPI("POST");
$status = $message = null;

//Obtención de los datos enviados
$datos = $api -> get_json_body();

//Si no existen datos
if($datos == null){
    //Devolución de error
    $status = "error";
    $message = "Faltan datos";
} else {
    //Capturar los datos
    $nombre = UtilsWeb::get_value_from_array_by_key("nombre", $datos);
    $nickname = UtilsWeb::get_value_from_array_by_key("nickname", $datos);
    $clave = UtilsWeb::get_value_from_array_by_key("clave", $datos);
    $tipo = UtilsWeb::get_value_from_array_by_key("tipo", $datos);

    //Comprobar que los datos capturados no son nulos y están en el rango
    if($nombre != null && $nickname != null && $clave != null &&
        ($tipo == "administrador" || $tipo == "profesor" || $tipo == "alumno") &&
        strlen($nombre) < 140  && strlen($nickname)
    ){
        //Crea la conexión para insertar los datos
        $conexion = new ContextDB("mysql:host=localhost;dbname=granada;charset=utf8mb4", "root", "");

        //Verificar que el usuario no existe
        $usuarioComprobar = $conexion -> execute_query(
            "SELECT nombre FROM usuarios WHERE nombre = :nombre",
            array(":nombre" => $nombre)
        );

        if($usuarioComprobar){
            $status = "error";
            $message = "El usuario ya existe";
        } else {
            //Inserta el usuario
            $conexion -> execute_query(
                "INSERT INTO usuarios VALUES (:nombre, :nickname, :clave, :tipo)",
                array(":nombre" => $nombre, ":nickname" => $nickname, ":clave" => md5($clave), ":tipo" => $tipo)
            );
            $status = "ok";
            $message = "Proceso finalizado";
        }

        //Finaliza la conexión
        $conexion -> finish();
    } else {
        $status = "error";
        $message = "Faltan datos o algunos son incorrectos";
    }
}

//Devolución final con un estado y un mensaje
$api -> return_json(array("status" => $status, "message" => $message));


?>