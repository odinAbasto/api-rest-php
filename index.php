<?php 
require_once 'db.class.php';
require_once 'ModeloController.php';
require_once 'api.REST.class.php';
require_once 'authModel.php';
require_once 'vendor/autoload.php'; 
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function PHPSTORM_META\type;

//request_uri: es la ruta relativa al directorio raiz
$base_url = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$request_uri = str_replace($base_url, '', $_SERVER['REQUEST_URI']);



$db = new DB(DB_SERVER, DB_USER,DB_PASSWORD ,DB_NAME);


$modeloController = new ModeloController($db);
$auth = new authModel();

$request_uri = explode('/', $request_uri, 2);
$endpoint = $request_uri[1];
$MiApi = new apiREST($modeloController, 'modelo');

//Obtecion del token en caso de que haya sido enviado por el cliente
$token = null;
if(isset($_SERVER['HTTP_API_KEY'])) $token = $_SERVER['HTTP_API_KEY'];
if($token===null) header("HTTP/1.1 401 Unauthorized");


//Verificacion de la validez del token
if($auth->validToken($token)){
    $MiApi->action($endpoint);   
}else{
    http_response_code(401);
    echo "Token no valido";
}

   
?>
