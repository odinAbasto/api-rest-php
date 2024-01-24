<?php 
require_once 'db.class.php';
require_once 'config.php';
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class authModel{
    private $db;
    public function __construct(){
        $this->db = new DB(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);
    }

    //Funcion que comprueba que las credenciales enviadas por el cliente son correctas
    public function login($login, $password) {
        $password = hash('sha256', $password);
        $query = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";    

            $result = $this->db->query($query);
            if($result->num_rows === 1){
                //echo var_dump($result->fetch_assoc());
                return $result->fetch_assoc();
                
            }else{
                return false;
            }        
    }

    //Verifica que el token sea valido y que se corresponde con el login y nombre de usuario contenidos por el mismo.
    public function validToken($token){
        try{
            $decoded = JWT::decode($token, new Key(KEY, 'HS256'));
        }catch(Exception $e){
            return false;
        }

        $login = $decoded->login;
        $name = $decoded->name;
        $query = "SELECT * FROM users WHERE login = '$login' AND name = '$name' and token = '$token'";
        $result = $this->db->query($query);    
        if($result->num_rows === 1){            
            return true;
        }
        return false;

    }
    
    // Guarda el token en la base de datos
    public function saveToken($login, $token) {
        $query = "UPDATE users SET token='$token' WHERE login='$login'";        
        return $this->db->query($query);
    }
    
    
}
?>