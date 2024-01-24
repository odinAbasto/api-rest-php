<?php
class apiREST{
    private $modeloController;
    private $resource;

    public function __construct($modeloController, $resource){
        $this->modeloController = $modeloController;
        $this->resource = $resource;
    }
    public function action($endpoint){
        $values = null;
        $url = explode('/',$endpoint);
        //El segundo parámetro es igual al nombre del recurso
        if($url[0]==='api' && $url[1]===$this->resource){

            //El endpoint tiene 3 parámetros
            if(sizeof($url)===3){
                
                //El segundo parámetro es una cadena vacía
                if($url[2]===''){
                    if($_SERVER['REQUEST_METHOD']==='GET'){
                        $values = $this->modeloController->getAll();
                        if(empty($values)){
                            header("HTTP/1.1 204 No Content");
                        }else{
                            echo json_encode($values);
                            header("HTTP/1.1 200 OK");
                        }
                    }else{
                        header("HTTP/1.1 405 Invalid Method");
                    }
                    //El segundo parametro es un valor numerico               
                }elseif(is_numeric($url[2])){
                    if($_SERVER['REQUEST_METHOD']==='GET' ){
                        $value = $this->modeloController->getById($url[2]);
                        if($value){
                            echo json_encode($value);
                            header("HTTP/1.1 200 OK");
                        }else{
                            header("HTTP/1.1 404 Not Found");
                        }
                    }else{
                        header("HTTP/1.1 405 Invalid Method");
                    }
                    //second parameter is la cadena 'store'     
                }
                //El endpoint tiene cuatro parámetros 
            }elseif(sizeof($url)===4){
                if($url[2]==='store'){   
                    
                    if($_SERVER['REQUEST_METHOD']==='POST'){
                        
                        if($this->existsPOST(['name','brand','year'])){
                            $value = $this->modeloController->createModelo($_POST['name'], $_POST['brand'], $_POST['year']);
                            echo json_encode($value);
                            
                            header("HTTP/1.1 201 Created");
                            
                        }else{
                            header("HTTP/1.1 400 Bad Request");
                        }
                    }else{
                        header("HTTP/1.1 405 Invalid Method");
                    }
                    
                }else{
                    header("HTTP/1.1 404 Not Found");
                }
                //El endpoint tiene cinco parametros
            }elseif(sizeof($url)===5){
                //El tercer parámtro es la cade 'destroy'
                if($url[2]==='destroy'){
                    //El cuatro parámetro es numericos
                    if(is_numeric($url[3])){
                        if($_SERVER['REQUEST_METHOD']==='DELETE'){
                            if($this->modeloController->delete($url[3])){
                                header("HTTP/1.1 204 No Content");
                            }else{
                                header("HTTP/1.1 404 Not Found");
                            }
                        }else{
                            header("HTTP/1.1 405 Invalid Method");
                        }
                    }else{
                        header("HTTP/1.1 400 Bad Request");
                    }
                }else{
                    header("HTTP/1.1 404 Not Found");
                }
            }else{
                header("HTTP/1.1 404 Not Found");
            }
        }else{
            header("HTTP/1.1 404 Not Found");
        }
    }

    public function existsPOST($array){
        foreach ($array as $value) {
            if(isset($_POST[$value])){
                if($_POST[$value] === ''){
                    return false;
                }
            }else{
                return false;
            }
        }
        return true;
    }

    
}
?>