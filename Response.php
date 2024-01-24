<?php
class Response {
    private $statusCode;
    private $message;
    private $data;

    public function __construct($statusCode, $message = null, $data = null) {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }

    public function send() {
        // Establecer el código de estado HTTP
        http_response_code($this->statusCode);

        // Establecer los headers como JSON
        header('Content-Type: application/json');

        // Construir el cuerpo de la respuesta
        $responseArray = array(
            'status_code' => $this->statusCode
        );

        if ($this->message !== null) {
            $responseArray['message'] = $this->message;
        }

        if ($this->data !== null) {
            $responseArray['data'] = $this->data;
        }

        // Enviar la respuesta como JSON
        echo json_encode($responseArray);
    }
}

?>
