<?php

require_once 'config.php';
require_once 'authModel.php';
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
////Obtener los datos de la peticion
////login y password
////comprobasremos las credenciales
////si son correctas devolveremos un token y lo almacenaremos en la base de datos
//// si no son corrrectas devolveremos un error





// Crear instancia de la base de datos
$auth = new authModel();

// Obtener los datos y decodificar JSON
$data = json_decode(file_get_contents("php://input"));

// Verificar que existen las credenciales y el metodo utilizado es el correcto
if (!$data || sizeof((array)$data) !== 2 ||$_SERVER['REQUEST_METHOD']!=='POST') {
    header("HTTP/1.1 401 Unauthorized");
    echo "Error de credenciales o metodo incorrecto";
    exit;
    
}

// Obtener las credenciales
$credentials = array_values((array)$data);

// Verificar que la validez de las credenciales
$user = $auth->login($credentials[0], $credentials[1]);
if (!$user) {
    echo "Las credenciales no son correctas";
    echo "Debe enviar un JSON con el siguiente formato: {\"login\":\"login\",\"password\":\"password\"}";
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

// Configuración del token
$payload = array(
    "login" => $user['login'], //login del usuario
    "name" => $user['name'], //nombre del usuario
    "iat" => time() //fecha de emision del token
);

// Generación del token y guardado en la base de datos
$token = JWT::encode($payload, KEY, 'HS256');


//Si el token es guardado en la base de datos exitosamente se devuelve al cliente
//Si no, se devuelve un error
if($auth->saveToken($user["login"],$token)){
    header("HTTP/1.1 200 OK");
    echo $token;
}else{
    header("HTTP/1.1 500 Internal Server Error");
}


?>