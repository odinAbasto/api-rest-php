<?php

class DB {
    private $servername;
    private $username;
    private $password;
    private $dbname ;
    private $port;
    private $conn;

    public function __construct($servername, $username, $password, $dbname, $port = 3306) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->port = $port;
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname, $this->port);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
    public function closeConnection() {
        $this->conn->close();
    }
    public function query($query) {
        return $this->conn->query($query);
        
    }
    public function getError() {
        return $this->conn->error;
    }
    public function getAffectedRows() {
        return $this->conn->affected_rows;
    }
    public function getInsertId() {
        return $this->conn->insert_id;
    }
}