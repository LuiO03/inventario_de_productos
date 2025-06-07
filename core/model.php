<?php

    require_once 'core/database.php';

    class Model{

        protected $PDO;
        function __construct(){
            //$this->db = Database::getInstance()->getConnection();// Obtener la conexión a la base de datos
            $conn = new Database();
            $this->PDO = $conn->connect();
        }
    }
?>